<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attachment;
use App\Models\Batch;
use App\Models\Chapter;
use App\Models\Institute;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.teacher.assignment.index');
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
