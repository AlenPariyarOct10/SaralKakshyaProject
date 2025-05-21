<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attachment;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load student with batch and subjects
        $student = Student::with(['batch.subjects', 'institutes'])
            ->where('id', $user->id)
            ->firstOrFail();

        // Get the student's batch ID
        $batchId = $student->batch ? $student->batch->id : null;

        // Fetch assignments for the student's batch
        $assignments = $batchId ? Assignment::with(['subject', 'batch', 'submissions'])
            ->where('batch_id', $batchId)
            ->where('status', 'active')
            ->get() : collect();

        // Separate submitted and pending assignments
        $submittedAssignments = $assignments->filter(function ($assignment) use ($user) {
            return $assignment->submissions()->where('student_id', $user->id)->exists();
        });

        $pendingAssignments = $assignments->filter(function ($assignment) use ($user) {
            return !$assignment->submissions()->where('student_id', $user->id)->exists();
        });

        return view('backend.student.assignment.index', compact('user', 'submittedAssignments', 'pendingAssignments'));
    }

    public function create()
    {
        $user = Auth::user();
        $batchId = $user->batch ? $user->batch->id : null;

        $assignments = $batchId ? Assignment::with(['subject', 'batch'])
            ->where('batch_id', $batchId)
            ->where('status', 'active')
            ->whereDoesntHave('submissions', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->get() : collect();

        return view('backend.student.assignment.create', compact('user', 'assignments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'assignment_id' => 'required|exists:assignments,id',
            'attachments' => 'required|array|min:1',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment = Assignment::where('id', $request->assignment_id)
            ->where('batch_id', $user->batch ? $user->batch->id : null)
            ->firstOrFail();

        if ($assignment->submissions()->where('student_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You have already submitted this assignment.');
        }

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $user->id,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        foreach ($request->file('attachments') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments/submissions', $fileName, 'public');

            $submission->attachments()->create([
                'title' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'path' => $filePath,
                'parent_type' => AssignmentSubmission::class,
                'parent_id' => $submission->id,
            ]);
        }

        Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', Student::class)
            ->where('parent_type', Assignment::class)
            ->where('parent_id', $assignment->id)
            ->whereNull('seen_at')
            ->update(['seen_at' => now(), 'read_at' => now()]);

        return redirect()->route('student.assignment.index')->with('success', 'Assignment submitted successfully.');
    }

    public function download($id)
    {
        $user = Auth::user();

        $attachment = Attachment::where('id', $id)
            ->where('parent_type', Assignment::class)
            ->firstOrFail();

            $assignment = Assignment::where('id', $attachment->parent_id)
                ->where('batch_id', $user->batch ? $user->batch->id : null)
                ->firstOrFail();


        $filePath = storage_path('app/public/' . $attachment->path);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $attachment->title);
    }

    public function viewAttachment($id)
    {
        $user = Auth::user();

        $attachment = Attachment::where('id', $id)
            ->where('parent_type', Assignment::class)
            ->firstOrFail();

        $filePath = storage_path('app/public/' . $attachment->path);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->file($filePath);
    }

    public function show(string $id)
    {
        $user = Auth::user();

        $assignment = Assignment::with(['subject', 'batch', 'attachments'])
            ->where('id', $id)
            ->where('batch_id', $user->batch ? $user->batch->id : null)
            ->firstOrFail();

        $submission = $assignment->submissions()
            ->where('student_id', $user->id)
            ->with('attachments')
            ->first();

        $submittedStatus = $submission ? true : false;

        Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', Student::class)
            ->where('parent_type', Assignment::class)
            ->where('parent_id', $assignment->id)
            ->whereNull('seen_at')
            ->update(['seen_at' => now(), 'read_at' => now()]);

        return view('backend.student.assignment.show', compact('assignment', 'user', 'submittedStatus', 'submission'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();

        $submission = AssignmentSubmission::where('id', $id)
            ->where('student_id', $user->id)
            ->where('status', 'submitted')
            ->with(['assignment', 'attachments'])
            ->firstOrFail();

        return view('backend.student.assignment.edit', compact('user', 'submission'));
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        $submission = AssignmentSubmission::where('id', $id)
            ->where('student_id', $user->id)
            ->where('status', 'submitted')
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'file' => 'sometimes|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('file')) {
            foreach ($submission->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments/submissions', $fileName, 'public');

            $submission->attachments()->create([
                'title' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'path' => $filePath,
                'parent_type' => AssignmentSubmission::class,
                'parent_id' => $submission->id,
            ]);
        }

        $submission->update(['submitted_at' => now()]);

        return redirect()->route('student.assignment.index')->with('success', 'Assignment submission updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = Auth::user();

        $submission = AssignmentSubmission::where('id', $id)
            ->where('student_id', $user->id)
            ->where('status', 'submitted')
            ->firstOrFail();

        foreach ($submission->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }

        $submission->delete();

        return redirect()->route('student.assignment.index')->with('success', 'Assignment submission deleted successfully.');
    }
}
