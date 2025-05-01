<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

    public function email()
    {
        $data = ['message' => 'This is a test email from Laravel!'];

        // Send the email using a simple view
        Mail::send('emails.test', $data, function ($message) {
            $message->to('oct10.alenpariyar@gmail.com') // Change this to the recipient's email address
            ->subject('Test Email from Laravel');
        });

        return 'Test email sent!';
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
            'file' => 'nullable|file|mimes:jpeg,jpg,png,pdf,docx,doc|max:20480',
        ]);

        // Create the announcement once
        $announcement = Announcement::create([
            ...$validated,
            'pinned' => $pinned,
            'notification' => $notification,
            'creator_type' => 'admin',
            'creator_id' => auth()->id(),
            'institute_id' => Auth::user()->institute?->id,
        ]);

        // Handle file upload if present
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

        return redirect()->back()->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('backend.admin.announcement.single', compact('announcement'));
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
