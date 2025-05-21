<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AssignmentSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('student')->user();
        $submissions = AssignmentSubmission::with(['assignment.subject', 'attachments'])
            ->where('student_id', $user->id)
            ->orderBy('submitted_at', 'desc')
            ->paginate(10);

        return view('backend.student.assignment-submission.index', compact('submissions', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $student = Auth::guard('student')->user();

        // Get assignments that are active and belong to the student's batch
        $assignments = Assignment::with('subject')
            ->where('batch_id', $student->batch)
            ->where('status', 'active')
            ->whereDoesntHave('submissions', function($query) use ($student) {
                $query->where('student_id', $student->id)
                    ->where('status', '!=', 'rejected');
            })
            ->orderBy('due_date', 'asc')
            ->get();

        return view('backend.student.assignment-submission.create', compact('assignments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validator = $request;

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = Auth::guard('student')->user();
        $assignment = Assignment::findOrFail($request->assignment_id);

        // Check if the student has already submitted this assignment (unless it was rejected)
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->where('status', '!=', 'rejected')
            ->first();

        if ($existingSubmission) {
            return redirect()->back()
                ->with('error', 'You have already submitted this assignment.')
                ->withInput();
        }

        try {
            $submission = new AssignmentSubmission();
            $submission->assignment_id = $assignment->id;
            $submission->student_id = $student->id;
            $submission->status = 'submitted';
            $submission->submitted_at = Carbon::now();
            $submission->description = $request->description;
            $submission->save();

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('assignment-submissions/' . $submission->id, 'public');

                    $attachment = new Attachment();
                    $attachment->parent_id = $submission->id;
                    $attachment->parent_type = AssignmentSubmission::class;
                    $attachment->file_path = $path;
                    $attachment->original_name = $file->getClientOriginalName();
                    $attachment->file_type = $file->getClientOriginalExtension();
                    $attachment->file_size = $file->getSize();
                    $attachment->save();
                }
            }

            return redirect()->route('student.assignment-submission.index')
                ->with('success', 'Assignment submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error submitting assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Auth::guard('student')->user();
        $submission = AssignmentSubmission::with(['assignment.subject', 'assignment.teacher', 'assignment.batch', 'attachments'])
            ->where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('backend.student.assignment-submission.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Auth::guard('student')->user();
        $submission = AssignmentSubmission::with(['assignment.subject', 'attachments'])
            ->where('id', $id)
            ->where('student_id', $student->id)
            ->where(function($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'rejected');
            })
            ->firstOrFail();

        return view('backend.student.assignment-submission.edit', compact('submission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'new_attachments' => 'nullable|array',
            'new_attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240',
            'comments' => 'nullable|string|max:1000',
            'confirmation' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = Auth::guard('student')->user();
        $submission = AssignmentSubmission::where('id', $id)
            ->where('student_id', $student->id)
            ->where(function($query) {
                $query->where('status', 'pending')
                    ->orWhere('status', 'rejected');
            })
            ->firstOrFail();

        try {
            // Update comments
            $submission->comments = $request->comments;

            // If the submission was rejected, reset status to pending
            if ($submission->status == 'rejected') {
                $submission->status = 'pending';
            }

            $submission->save();

            // Handle removal of existing attachments
            if ($request->has('remove_attachments')) {
                foreach ($request->remove_attachments as $attachmentId) {
                    $attachment = Attachment::where('id', $attachmentId)
                        ->where('parent_id', $submission->id)
                        ->where('parent_type', AssignmentSubmission::class)
                        ->first();

                    if ($attachment) {
                        Storage::disk('public')->delete($attachment->file_path);
                        $attachment->delete();
                    }
                }
            }

            // Handle new file uploads
            if ($request->hasFile('new_attachments')) {
                foreach ($request->file('new_attachments') as $file) {
                    $path = $file->store('assignment-submissions/' . $submission->id, 'public');

                    $attachment = new Attachment();
                    $attachment->parent_id = $submission->id;
                    $attachment->parent_type = AssignmentSubmission::class;
                    $attachment->file_path = $path;
                    $attachment->original_name = $file->getClientOriginalName();
                    $attachment->file_type = $file->getClientOriginalExtension();
                    $attachment->file_size = $file->getSize();
                    $attachment->save();
                }
            }

            return redirect()->route('student.assignment-submission.show', $submission->id)
                ->with('success', 'Submission updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating submission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download the specified attachment.
     */

    public function download($id)
    {
        $user = Auth::user();

        $attachment = Attachment::where('id', $id)
            ->where('parent_type', AssignmentSubmission::class)
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


    /**
     * View the specified attachment.
     */
    public function viewAttachment($id)
    {
        $user = Auth::user();

        $attachment = Attachment::where('id', $id)
            ->where('parent_type', AssignmentSubmission::class)
            ->firstOrFail();

        $filePath = storage_path('app/public/' . $attachment->path);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->file($filePath);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Auth::guard('student')->user();
        $submission = AssignmentSubmission::where('id', $id)
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            // Delete all attachments
            foreach ($submission->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }

            $submission->delete();

            return redirect()->route('student.assignment-submission.index')
                ->with('success', 'Submission deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting submission: ' . $e->getMessage());
        }
    }
}
