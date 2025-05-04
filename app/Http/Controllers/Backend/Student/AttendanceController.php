<?php

namespace App\Http\Controllers\Backend\Student;

use App\Exports\Admin\StudentExport;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Attendance;
use App\Models\Institute;
use App\Models\Student;
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

    public function user_info_for_face_recognition(Request $request)
    {
        $uid = $request->id;
        $institute_id = $request->institute_id; // ✅ Fix the typo here

        try {
            $user = Student::findOrFail($uid);
            $institute = Institute::findOrFail($institute_id);

            $user->institute = $institute;

            return response()->json([
                'success' => true,
                'student' => $user,
                'institute' => $institute,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or error occurred.',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function updateFacePhotos(Request $request)
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
        $response = Http::post('http://127.0.0.1:5000/update-face', [
            'student_id' => $parentId,
            'institute_id' => $parentId,
            'images'     => $base64Images,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Face photos saved and sent to Python.',
            'flask_response' => $response->json()
        ]);
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
            'institute_id' => $parentId,
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

    public function mark_attendance(Request $request)
    {
        $student_id = $request->student_id;
        $institute_id = $request->institute_id;
        $timestamp = $request->timestamp;
        protected $fillable = ['attendee_type', 'attendee_id', 'subject_id', 'date','attended_at', 'status', 'method', 'creator_type', 'creator_id', 'is_verified', 'remarks'];

        Attendance::create([
            "attendee_type"=>"student",
            "attendee_id" => $student_id,
            "subject_id" =>
        ])
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
