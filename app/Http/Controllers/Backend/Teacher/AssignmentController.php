<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Events\AssignmentCreated;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attachment;
use App\Models\Batch;
use App\Models\Chapter;
use App\Models\Institute;
use App\Models\Notification;
use App\Models\SeenStatus;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.teacher.assignment.index');
    }

    public function filterAssignments(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();

        $query = Assignment::query()
            ->where('teacher_id', $teacherId)
            ->with(['subject', 'batch.program.department', 'attachments', 'assignmentSubmissions']);

        // Apply filters
        if ($request->filled('department_id')) {
            $query->whereHas('batch.program.department', function ($q) use ($request) {
                $q->where('id', $request->department_id);
            });
        }

        if ($request->filled('program_id')) {
            $query->whereHas('batch.program', function ($q) use ($request) {
                $q->where('id', $request->program_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Pagination
        $assignments = $query->paginate(10);

        // Map with additional info
        $assignments->getCollection()->transform(function ($assignment) {
            $now = now();
            $dueDate = Carbon::parse($assignment->due_date);
            $diffInDays = $now->diffInDays($dueDate, false);

            if ($diffInDays > 0) {
                $assignment->remaining_days = "Due in $diffInDays " . Str::plural('day', $diffInDays);
            } elseif ($diffInDays < 0) {
                $assignment->remaining_days = "Overdue by " . abs($diffInDays) . " " . Str::plural('day', abs($diffInDays));
            } else {
                $assignment->remaining_days = "Due today";
            }

            $assignment->department = $assignment->batch->program->department->name ?? null;
            $assignment->program = $assignment->batch->program->name ?? null;

            $assignment->due_date_human = $dueDate->diffForHumans($now, [
                'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS,
            ]);

            $assignment->submissions_count = $assignment->assignmentSubmissions?->count();
            $assignment->total_students = $assignment->batch->students?->count() ?? 0;

            return $assignment;
        });

        return response()->json([
            'data' => $assignments,
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
            ]
        ]);
    }

    public function myAssignments()
    {
        $teacher = Teacher::where('id', Auth::guard('teacher')->user()->id)->first();

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['subject', 'batch', 'attachments', 'assignmentSubmissions'])
            ->get()
            ->map(function ($assignment) {

                $assignment->load(['subject', 'batch', 'attachments']);
                $dueDate = Carbon::parse($assignment->due_date);
                $now = now();

                $diffInDays = $now->diffInDays($dueDate, false);

                if ($diffInDays > 0) {
                    $assignment->remaining_days = "Due in $diffInDays " . Str::plural('day', $diffInDays);
                } elseif ($diffInDays < 0) {
                    $assignment->remaining_days = "Overdue by " . abs($diffInDays) . " " . Str::plural('day', abs($diffInDays));
                } else {
                    $assignment->remaining_days = "Due today";
                }

                $assignment->department = $assignment->batch->program->department->name ?? null;
                $assignment->program = $assignment->batch->program->name ?? null;

                // Optional: for more natural date representation
                $assignment->due_date_human = $dueDate->diffForHumans($now, [
                    'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                    'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS,
                ]);

                return $assignment;
            });


        return response()->json([
            'assignments' => $assignments,
            'success' => 1
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacher = Teacher::where('id', Auth::guard('teacher')->user()->id)->first();
        $batches = $teacher->teachingBatches();

        $subjects = $teacher->subjectTeacherMappings()->with('subject')->get();
        return view('backend.teacher.assignment.create', compact('batches', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'sub_chapter_id' => 'nullable|exists:chapters,id',
            'description' => 'required|string',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'due_time' => 'required',
            'full_marks' => 'required|integer|min:1|max:100',
            'status' => 'required|in:draft,active',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);

        try {


            $assignment = DB::transaction(function () use ($validated, $request) {
                $semester = Subject::find($validated['subject_id'])->semester;
                $program_id = Subject::find($validated['subject_id'])->program_id;
                $batch_id = Batch::where('program_id', $program_id)
                    ->where('semester', $semester)
                    ->first()->id;

                $assignment = Assignment::create([
                    'teacher_id' => Auth::guard('teacher')->user()->id,
                    'subject_id' => $validated['subject_id'],
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'assigned_date' => $validated['assigned_date'],
                    'due_date' => $validated['due_date'],
                    'due_time' => $validated['due_time'],
                    'semester' => $semester,
                    'program_id' => $program_id,
                    'batch_id' => $batch_id,
                    'status' => $validated['status'],
                    'full_marks' => $validated['full_marks'],
                    'chapter_id' => $validated['chapter_id'],
                    'sub_chapter_id' => $validated['sub_chapter_id'] ?? null,
                ]);

                // Handle file uploads if any
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('assignments/' . $assignment->id, 'public');

                        Attachment::create([
                            'title' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                            'parent_type' => Assignment::class,
                            'parent_id' => $assignment->id,
                            'path' => $path
                        ]);
                    }
                }

                return $assignment;
            });

            event(new AssignmentCreated($assignment));


            return redirect()->route('teacher.assignment.show', $assignment)
                ->with('success', 'Assignment created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        // Eager load all necessary relationships
        $assignment->load([
            'subject',
            'chapter',
            'subChapter',
            'attachments',
            'teacher' // Useful for showing teacher info
        ]);

        // Verify ownership - more explicit authorization
        if ($assignment->teacher_id !== auth()->guard('teacher')->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('backend.teacher.assignment.show', [
            'assignment' => $assignment,
            'success' => session('success') // Pass flash message to view
        ]);
    }

    public function downloadAttachment($assignmentId, $attachmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $attachment = Attachment::where('parent_id', $assignmentId)
            ->where('parent_type', Assignment::class)
            ->findOrFail($attachmentId);

        // Verify the teacher owns this assignment
        if ($assignment->teacher_id != Auth::guard('teacher')->user()->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->path, $attachment->title);
    }

    public function viewAttachment($assignmentId, $attachmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $attachment = Attachment::where('parent_id', $assignmentId)
            ->where('parent_type', Assignment::class)
            ->findOrFail($attachmentId);

        // Verify the teacher owns this assignment
        if ($assignment->teacher_id != Auth::guard('teacher')->user()->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404);
        }

        // Return the file directly
        return Storage::disk('public')->response($attachment->path, $attachment->title);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::where('id', Auth::guard('teacher')->user()->id)->first();
        $batches = $teacher->teachingBatches();

        $subjects = $teacher->subjectTeacherMappings()->with('subject')->get();

        $assignment = Assignment::findOrFail($id);
        return view('backend.teacher.assignment.edit', compact('batches', 'subjects', 'assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the assignment
        $assignment = Assignment::findOrFail($id);

        // Check if the logged-in teacher owns this assignment
        if ($assignment->teacher_id !== Auth::guard('teacher')->user()->id) {
            return redirect()->back()->with('error', 'You do not have permission to update this assignment.');
        }

        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required|exists:chapters,id',
            'sub_chapter_id' => 'nullable|exists:chapters,id',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'due_time' => 'required',
            'full_marks' => 'required|integer|min:1|max:100',
            'status' => 'required|in:draft,active',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'exists:attachments,id',
        ]);

        try {
            $result = DB::transaction(function () use ($validated, $request, $assignment) {
                $semester = Subject::find($validated['subject_id'])->semester;
                $program_id = Subject::find($validated['subject_id'])->program_id;
                $batch_id = Batch::where('program_id', $program_id)
                    ->where('semester', $semester)
                    ->first()->id;

                // Update the assignment
                $assignment->update([
                    'subject_id' => $validated['subject_id'],
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'due_date' => $validated['due_date'],
                    'due_time' => $validated['due_time'],
                    'semester' => $semester,
                    'program_id' => $program_id,
                    'batch_id' => $batch_id,
                    'status' => $validated['status'],
                    'full_marks' => $validated['full_marks'],
                    'chapter_id' => $validated['chapter_id'],
                    'sub_chapter_id' => $validated['sub_chapter_id'] ?? null,
                ]);

                // Handle deletion of attachments if any are selected for deletion
                if ($request->has('delete_attachments')) {
                    foreach ($request->delete_attachments as $attachmentId) {
                        $attachment = Attachment::where('id', $attachmentId)
                            ->where('parent_type', Assignment::class)
                            ->where('parent_id', $assignment->id)
                            ->first();

                        if ($attachment) {
                            // Delete the file from storage
                            if (Storage::disk('public')->exists($attachment->path)) {
                                Storage::disk('public')->delete($attachment->path);
                            }

                            // Delete the attachment record
                            $attachment->delete();
                        }
                    }
                }

                // Handle new file uploads if any
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('assignments/' . $assignment->id, 'public');

                        Attachment::create([
                            'title' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                            'parent_type' => Assignment::class,
                            'parent_id' => $assignment->id,
                            'path' => $path
                        ]);
                    }
                }

                return $assignment;
            });

            return redirect()->route('teacher.assignment.show', $assignment)
                ->with('success', 'Assignment updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating assignment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        try {
            DB::transaction(function () use ($assignment) {
                // Delete all related attachments and their files
                if ($assignment->attachments->isNotEmpty()) {
                    foreach ($assignment->attachments as $attachment) {
                        // Delete the physical file
                        Storage::disk('public')->delete($attachment->path);
                        // Delete the attachment record
                        $attachment->delete();
                    }
                }

                $assignment->delete();
            });

            return redirect()->route('teacher.assignment.index')
                ->with('success', 'Assignment deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting assignment: ' . $e->getMessage());
        }
    }

    public function getChapters(int $id): JsonResponse
    {
        $chapters = Chapter::where('subject_id', $id)
            ->where('level', 1)
            ->get();

        return response()->json($chapters);
    }

    public function getSubChapters(int $id): JsonResponse
    {
        $chapters = Chapter::where('parent_id', $id)
            ->get();

        return response()->json($chapters);
    }


}
