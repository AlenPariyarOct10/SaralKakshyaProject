<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.student.attendance.index');
    }


    public function saveFacePhotos(Request $request)
    {
        $photos = $request->only(['photo_1', 'photo_2', 'photo_3', 'photo_4', 'photo_5']);

        if (count($photos) !== 5) {
            return response()->json(['success' => false, 'message' => 'Please upload exactly 5 valid photos.'], 422);
        }

        $user = auth()->user();
        $parentId = $user->id;
        $base64Images = [];

        foreach ($photos as $photo) {
            if ($photo && $photo->isValid()) {
                $extension = $photo->getClientOriginalExtension();
                $filename = uniqid('face_') . '.' . $extension;
                $path = $photo->storeAs('uploads/faces', $filename, 'public');

                Attachment::create([
                    'title'       => 'Face Photo ' . $parentId,
                    'file_type'   => $extension,
                    'parent_type' => get_class($user),
                    'parent_id'   => $parentId,
                    'path'        => $path,
                ]);

                $imageContent = Storage::disk('public')->get($path);
                $base64Images[] = base64_encode($imageContent);
            }
        }

        // Send to Python Flask API
        $response = Http::post('http://127.0.0.1:5000/register-face', [
            'student_id' => $parentId,
            'images'     => $base64Images,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Face photos saved and sent to Python.',
            'flask_response' => $response->json()
        ]);
    }



    public function setup_index()
    {
        $user = Auth::user();
        return view('backend.student.attendance.setup-face', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
