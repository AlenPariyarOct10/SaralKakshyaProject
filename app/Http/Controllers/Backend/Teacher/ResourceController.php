<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Chapter;
use App\Models\Link;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacherId = Auth::guard('teacher')->id();
        $teacher = Teacher::findOrFail($teacherId);

        // Get subjects taught by the teacher
        $subjects = $teacher->subjectTeacherMappings()->with('subject')->get()->pluck('subject');

        // Get resources created by the teacher with filters
        $resources = Resource::with(['subject', 'links', 'attachments'])
            ->where('teacher_id', $teacherId)
            ->when(request('subject_id'), function ($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when(request('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('backend.teacher.resources.index', compact('resources', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacherId = Auth::guard('teacher')->id();
        $teacher = Teacher::findOrFail($teacherId);

        // Get all subjects through the subjectTeacherMappings relationship
        $subjects = $teacher->subjectTeacherMappings()
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->values();

        return view('backend.teacher.resources.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:book,video,question,link,other',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'sub_chapter_id' => 'nullable|exists:chapters,id',
            'links' => 'nullable|array',
            'links.*.title' => 'required_with:links|string|max:255',
            'links.*.link_type' => 'required_with:links|string|in:website,youtube,drive,other',
            'links.*.url' => 'required_with:links|url',
            'attachments' => 'nullable|array',
            'attachments.*.title' => 'required_with:attachments|string|max:255',
            'attachments.*.file_type' => 'required_with:attachments|string|in:document,image,video,audio,other',
            'attachments.*.file' => 'required_with:attachments|file|max:10240', // 10MB max
        ]);

        $teacherId = Auth::guard('teacher')->id();

        // Create the resource
        $resource = Resource::create([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'teacher_id' => $teacherId,
            'subject_id' => $request->subject_id,
            'chapter_id' => $request->chapter_id,
            'sub_chapter_id' => $request->sub_chapter_id,
        ]);

        // Save links if any
        if ($request->has('links')) {
            foreach ($request->links as $linkData) {
                $resource->links()->create([
                    'title' => $linkData['title'],
                    'link_type' => $linkData['link_type'],
                    'url' => $linkData['url'],
                ]);
            }
        }

        // Save attachments if any
        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachmentData) {
                $file = $attachmentData['file'];
                $path = $file->store('attachments', 'public');

                $resource->attachments()->create([
                    'title' => $attachmentData['title'],
                    'file_type' => $attachmentData['file_type'],
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('teacher.resources.index')
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();
        $resource = Resource::with(['subject', 'chapter', 'subChapter', 'links'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($id);

        $attachments = $resource->attachments;

        return view('backend.teacher.resources.show', compact('resource', 'attachments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();
        $teacher = Teacher::findOrFail($teacherId);

        // Get the resource with relationships
        $resource = Resource::with(['links', 'attachments'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($id);

        // Get subjects taught by the teacher
        $subjects = $teacher->subjectTeacherMappings()
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->values();

        // Get chapters for the selected subject
        $chapters = Chapter::where('subject_id', $resource->subject_id)->get();

        // Get sub-chapters for the selected chapter if exists
        $subChapters = [];
        if ($resource->chapter_id) {
            $subChapters = Chapter::where('parent_id', $resource->chapter_id)->get();
        }

        return view('backend.teacher.resources.edit', compact('resource', 'subjects', 'chapters', 'subChapters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacherId = Auth::guard('teacher')->id();
        $resource = Resource::where('teacher_id', $teacherId)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'sub_chapter_id' => 'nullable|exists:chapters,id',
            'links' => 'nullable|array',
            'links.*.id' => 'sometimes|exists:links,id',
            'links.*.title' => 'required_with:links|string|max:255',
            'links.*.link_type' => 'required_with:links|string|in:website,youtube,drive,other',
            'links.*.url' => 'required_with:links|url',
            'attachments' => 'nullable|array',
            'attachments.*.title' => 'required_with:attachments|string|max:255',
            'attachments.*.file_type' => 'required_with:attachments|string|in:document,image,video,audio,other',
            'attachments.*.file' => 'sometimes|file|max:10240', // 10MB max
            'deleted_links' => 'nullable|array',
            'deleted_links.*' => 'exists:links,id',
            'deleted_attachments' => 'nullable|array',
            'deleted_attachments.*' => 'exists:attachments,id',
        ]);

        // Update the resource
        $resource->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'chapter_id' => $request->chapter_id,
            'sub_chapter_id' => $request->sub_chapter_id,
        ]);

        // Handle links
        if ($request->has('links')) {
            foreach ($request->links as $linkData) {
                if (isset($linkData['id'])) {
                    // Update existing link
                    $link = $resource->links()->findOrFail($linkData['id']);
                    $link->update([
                        'title' => $linkData['title'],
                        'link_type' => $linkData['link_type'],
                        'url' => $linkData['url'],
                    ]);
                } else {
                    // Create new link
                    $resource->links()->create([
                        'title' => $linkData['title'],
                        'link_type' => $linkData['link_type'],
                        'url' => $linkData['url'],
                    ]);
                }
            }
        }

        // Handle deleted links
        if ($request->has('deleted_links')) {
            $resource->links()->whereIn('id', $request->deleted_links)->delete();
        }

        // Handle attachments
        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachmentData) {
                if (isset($attachmentData['file'])) {
                    $file = $attachmentData['file'];
                    $path = $file->store('attachments', 'public');

                    $resource->attachments()->create([
                        'title' => $attachmentData['title'],
                        'file_type' => $attachmentData['file_type'],
                        'path' => $path,
                    ]);
                }
            }
        }

        // Handle deleted attachments
        if ($request->has('deleted_attachments')) {
            $attachmentsToDelete = $resource->attachments()->whereIn('id', $request->deleted_attachments)->get();

            foreach ($attachmentsToDelete as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }
        }

        return redirect()->route('teacher.resources.show', $resource->id)
            ->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacherId = Auth::guard('teacher')->id();
        $resource = Resource::with(['links', 'attachments'])
            ->where('teacher_id', $teacherId)
            ->findOrFail($id);

        // Delete attachments files from storage
        foreach ($resource->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }

        // Delete the resource (cascading deletes will handle links and attachments)
        $resource->delete();

        return redirect()->route('teacher.resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}
