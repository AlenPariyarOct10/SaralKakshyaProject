<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\Program;
use App\Models\SubjectTeacherMapping;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Teacher::findOrFail(auth()->user()->id);

        $teacherId = $user->id;

        $departments = $user->subjectTeacherMappings()
            ->with('subject.department')
            ->get()
            ->pluck('subject.department')
            ->unique('id')
            ->values();

        $programs = Program::whereHas('subjects.subjectTeacherMappings', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->get();

        $programIds = $programs->pluck('id')->toArray();


// Filter announcements by these department IDs
        $announcements = Announcement::where('institute_id', session()->get('institute_id'))
            ->whereIn('program_id', $programIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.teacher.announcement.index', compact(
            'departments', 'announcements', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Teacher::findOrFail(auth()->user()->id);
        $teacherId = $user->id;

        $programs = Program::whereHas('subjects.subjectTeacherMappings', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->get();


        return view('backend.teacher.announcement.create', compact('programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:regular,important,urgent',
            'program_id' => 'required|exists:programs,id',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,docx,doc|max:20480'
        ]);

        // Get the program to determine department
        $program = Program::findOrFail($validated['program_id']);

        // Create the announcement
        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'program_id' => $validated['program_id'],
            'department_id' => $program->department_id,
            'institute_id' => $program->institute_id,
            'creator_type' => 'teacher',
            'creator_id' => auth()->user()->id,
            'pinned' => false, // Default to false for teacher announcements
            'notification' => false, // Default to false for teacher announcements
        ]);

        // Handle file uploads if present
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('announcements', 'public');

                $announcement->attachments()->create([
                    'title' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'path' => $path,
                ]);
            }
        }

        // Return success response
        return redirect()->route('teacher.announcement.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Teacher::findOrFail(auth()->user()->id);

        $teacherId = $user->id;
        $programs = Program::whereHas('subjects.subjectTeacherMappings', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->get();

        $announcement = Announcement::findOrFail($id);
        return view('backend.teacher.announcement.show', compact('user','programs', 'announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Teacher::findOrFail(auth()->user()->id);

        $teacherId = $user->id;
        $programs = Program::whereHas('subjects.subjectTeacherMappings', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->get();


        $announcement = Announcement::findOrFail($id);
        return view('backend.teacher.announcement.edit', compact('announcement','programs',  'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:regular,important,urgent',
            'program_id' => 'required|exists:programs,id',
            'attachments.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf,docx,doc|max:20480'
        ]);

        if(!isset($validated['is_pinned'])){
            $validated['is_pinned'] = false;
        }else{
            $validated['is_pinned'] = true;
        }

        // Get the existing announcement
        $announcement = Announcement::findOrFail($id);

        // Get the program to determine department
        $program = Program::findOrFail($validated['program_id']);

        // Update the announcement
        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'program_id' => $validated['program_id'],
            'department_id' => $program->department_id,
            'institute_id' => $program->institute_id,
            'is_pinned'=>$validated['is_pinned']
        ]);

        // Handle file uploads if present
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('announcements', 'public');

                $announcement->attachments()->create([
                    'title' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('teacher.announcement.index')->with('success', 'Announcement updated successfully.');
    }

    function deleteAttachment($id)
    {
        Attachment::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Attachment deleted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);

        // Delete associated attachments
        foreach ($announcement->attachments as $attachment) {
            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }

            $attachment->delete();
        }

        $announcement->delete();

        return redirect()->route('teacher.announcement.index')->with('success', 'Announcement deleted successfully.');
    }

}
