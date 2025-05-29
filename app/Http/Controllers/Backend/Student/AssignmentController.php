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
use Carbon\Carbon;

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

        if (!$batchId) {
            return view('backend.student.assignment.index', [
                'user' => $user,
                'submittedAssignments' => collect(),
                'pendingAssignments' => collect(),
                'assignmentStats' => [
                    'total' => 0,
                    'submitted' => 0,
                    'pending' => 0,
                    'graded' => 0,
                    'overdue' => 0,
                    'due_soon' => 0,
                    'this_month' => 0
                ]
            ]);
        }

        // Fetch assignments for the student's batch
        $assignments = Assignment::with(['subject', 'batch', 'assignmentSubmissions', 'attachments'])
            ->where('batch_id', $batchId)
            ->where('status', 'active')
            ->orderBy('due_date', 'asc')
            ->get();

        // Get submitted assignment IDs for this student
        $submittedAssignmentIds = AssignmentSubmission::where('student_id', $user->id)
            ->pluck('assignment_id')
            ->toArray();

        // Separate submitted and pending assignments
        $submittedAssignments = $assignments->filter(function ($assignment) use ($submittedAssignmentIds) {
            return in_array($assignment->id, $submittedAssignmentIds);
        });

        $pendingAssignments = $assignments->filter(function ($assignment) use ($submittedAssignmentIds) {
            return !in_array($assignment->id, $submittedAssignmentIds);
        });

        // Calculate assignment statistics
        $now = Carbon::now();
        $weekFromNow = $now->copy()->addWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        // Count overdue assignments (pending assignments past due date)
        $overdueCount = $pendingAssignments->filter(function($assignment) use ($now) {
            return $assignment->due_date && Carbon::parse($assignment->due_date)->isPast();
        })->count();

        // Count assignments due soon (within next 7 days)
        $dueSoonCount = $pendingAssignments->filter(function($assignment) use ($now, $weekFromNow) {
            if (!$assignment->due_date) return false;
            $dueDate = Carbon::parse($assignment->due_date);
            return $dueDate->isFuture() && $dueDate->lte($weekFromNow);
        })->count();

        // Count graded assignments
        $gradedCount = AssignmentSubmission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->count();

        // Count assignments created this month
        $thisMonthCount = $assignments->filter(function($assignment) use ($startOfMonth) {
            return Carbon::parse($assignment->created_at)->gte($startOfMonth);
        })->count();

        $assignmentStats = [
            'total' => $assignments->count(),
            'submitted' => $submittedAssignments->count(),
            'pending' => $pendingAssignments->count(),
            'graded' => $gradedCount,
            'overdue' => $overdueCount,
            'due_soon' => $dueSoonCount,
            'this_month' => $thisMonthCount
        ];

        return view('backend.student.assignment.index', compact(
            'user',
            'submittedAssignments',
            'pendingAssignments',
            'assignmentStats'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $batchId = $user->batch ? $user->batch->id : null;

        $assignments = $batchId ? Assignment::with(['subject', 'batch'])
            ->where('batch_id', $batchId)
            ->where('status', 'active')
            ->whereDoesntHave('assignmentSubmissions', function ($query) use ($user) {
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
            'description' => 'nullable|string|max:255',
            'attachments' => 'required|array|min:1',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment = Assignment::where('id', $request->assignment_id)
            ->where('batch_id', $user->batch ? $user->batch->id : null)
            ->firstOrFail();

        if ($assignment->assignmentSubmissions()->where('student_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You have already submitted this assignment.');
        }

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $user->id,
            'submitted_at' => now(),
            'description' => $request->description,
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

        // Mark related notifications as seen
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

        $submission = $assignment->assignmentSubmissions()
            ->where('student_id', $user->id)
            ->with('attachments')
            ->first();

        $submittedStatus = $submission ? true : false;

        // Mark related notifications as seen
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
            // Delete old attachments
            foreach ($submission->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            // Upload new file
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

        // Delete associated files
        foreach ($submission->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }

        $submission->delete();

        return redirect()->route('student.assignment.index')->with('success', 'Assignment submission deleted successfully.');
    }

    /**
     * Get assignment statistics for API calls
     */
    public function getStats()
    {
        $user = Auth::user();
        $batchId = $user->batch ? $user->batch->id : null;

        if (!$batchId) {
            return response()->json([
                'total' => 0,
                'submitted' => 0,
                'pending' => 0,
                'graded' => 0,
                'overdue' => 0,
                'due_soon' => 0,
                'this_month' => 0
            ]);
        }

        $assignments = Assignment::where('batch_id', $batchId)
            ->where('status', 'active')
            ->get();

        $submittedCount = AssignmentSubmission::where('student_id', $user->id)->count();
        $gradedCount = AssignmentSubmission::where('student_id', $user->id)
            ->where('status', 'graded')
            ->count();

        $now = Carbon::now();
        $weekFromNow = $now->copy()->addWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        $submittedAssignmentIds = AssignmentSubmission::where('student_id', $user->id)
            ->pluck('assignment_id')
            ->toArray();

        $pendingAssignments = $assignments->whereNotIn('id', $submittedAssignmentIds);

        $overdueCount = $pendingAssignments->filter(function($assignment) use ($now) {
            return $assignment->due_date && Carbon::parse($assignment->due_date)->isPast();
        })->count();

        $dueSoonCount = $pendingAssignments->filter(function($assignment) use ($now, $weekFromNow) {
            if (!$assignment->due_date) return false;
            $dueDate = Carbon::parse($assignment->due_date);
            return $dueDate->isFuture() && $dueDate->lte($weekFromNow);
        })->count();

        $thisMonthCount = $assignments->filter(function($assignment) use ($startOfMonth) {
            return Carbon::parse($assignment->created_at)->gte($startOfMonth);
        })->count();

        return response()->json([
            'total' => $assignments->count(),
            'submitted' => $submittedCount,
            'pending' => $pendingAssignments->count(),
            'graded' => $gradedCount,
            'overdue' => $overdueCount,
            'due_soon' => $dueSoonCount,
            'this_month' => $thisMonthCount
        ]);
    }
}
