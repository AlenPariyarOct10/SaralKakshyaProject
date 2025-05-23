<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        // Get assignments created by the teacher
        $assignments = Assignment::where('teacher_id', $teacherId)->get();

        // Get batches taught by the teacher
        $batches = Batch::select('batches.*')
            ->join('subjects', function($join) {
                $join->on('subjects.program_id', '=', 'batches.program_id')
                    ->whereColumn('subjects.semester', '=', 'batches.semester');
            })
            ->join('subject_teacher_mappings', 'subject_teacher_mappings.subject_id', '=', 'subjects.id')
            ->where('subject_teacher_mappings.teacher_id', $teacherId)
            ->distinct()
            ->get();

        // Build query for submissions
        $query = AssignmentSubmission::with(['student', 'assignment.subject'])
            ->whereHas('assignment', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            });

        // Apply filters
        if ($request->has('assignment_id') && $request->assignment_id) {
            $query->where('assignment_id', $request->assignment_id);
        }

        if ($request->has('batch_id') && $request->batch_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('batch_id', $request->batch_id);
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('submitted_at', $request->date);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $submissions = $query->latest('submitted_at')->paginate(10);

        return view('backend.teacher.assignment-submission.index', compact('submissions', 'assignments', 'batches'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $submission = AssignmentSubmission::with([
            'student',
            'assignment.subject',
            'assignment.attachments',
            'attachments'
        ])
            ->whereHas('assignment', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->findOrFail($id);

        return view('backend.teacher.assignment-submission.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $submission = AssignmentSubmission::with([
            'student',
            'assignment.subject',
            'assignment.attachments',
            'attachments'
        ])
            ->whereHas('assignment', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->findOrFail($id);

        return view('backend.teacher.assignment-submissions.edit', compact('submission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacherId = Auth::guard('teacher')->id();

        $submission = AssignmentSubmission::whereHas('assignment', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->findOrFail($id);

        $validated = $request->validate([
            'marks' => 'nullable|numeric|min:0|max:' . $submission->assignment->full_marks,
            'feedback' => 'nullable|string',
            'status' => 'required|in:submitted,graded',
        ]);

        $submission->update($validated);

        return redirect()->route('teacher.assignment-submissions.show', $submission->id)
            ->with('success', 'Submission graded successfully.');
    }

    /**
     * Download an attachment.
     */
    public function download(string $attachmentId)
    {
        $teacherId = Auth::guard('teacher')->id();

        $attachment = \App\Models\Attachment::whereHasMorph(
            'parent',
            [AssignmentSubmission::class],
            function($q) use ($teacherId) {
                $q->whereHas('assignment', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                });
            }
        )->findOrFail($attachmentId);

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }
}
