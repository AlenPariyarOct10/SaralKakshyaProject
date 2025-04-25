<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view('backend.admin.announcement', compact('user'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        return view('backend.admin.upload-announcement', compact('user'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pinned = $request->has('pinned');
        $notification = $request->has('notification');

        $validated = $request->validate([
            'title' => 'string|required|max:255',
            'department_id' => 'nullable',
            'program_id' => 'nullable',
            'type' => 'required|string',
            'content' => 'required|string|max:500',
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf,docx,doc|max:20480',

        ]);


        // Create the Announcement
        $announcement = Announcement::create([
            'title' => $validated['title'],
            'department_id' => $validated['department_id'],
            'program_id' => $validated['program_id'],
            'type' => $validated['type'],
            'content' => $validated['content'],
            'pinned' => $pinned,
            'notification' => $notification,
            'creator_type' => 'admin',
            'creator_id' => auth()->id(),
        ]);



// Save announcement
        $announcement = Announcement::create([
            ...$validated,
            'pinned' => $pinned,
            'notification' => $notification,
            'creator_type' => 'admin',
            'creator_id' => auth()->id(),
        ]);

// Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('attachments', $fileName, 'public');

            $announcement->attachments()->create([
                'title' => $fileName,
                'file_type' => $file->getClientOriginalExtension(),
                'path' => $filePath,
            ]);
        }

        return redirect()->back()->with('success', 'Announcement created with attachment.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
