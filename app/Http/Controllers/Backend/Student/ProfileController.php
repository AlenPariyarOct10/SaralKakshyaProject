<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\InstituteStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('student')->user();
        $program = InstituteStudent::where('student_id', $user->id)
            ->with('program')
            ->first();
        $batch = Batch::where('id', $user->batch_id)->first();
        return view('backend.student.profile', compact('user', 'program', 'batch'));
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

    public function update_profile_picture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,png,gif|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        if ($user->profile_picture && Storage::exists('public/profile_pictures/' . $user->profile_picture)) {
            Storage::delete('public/profile_pictures/' . $user->profile_picture);
        }


        // Upload new profile picture
        $file = $request->file('profile_picture');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('profile_pictures', $fileName, 'public');


        // Update user profile picture in the database
        $user->profile_picture = $fileName;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile picture updated successfully!',
            'image_url' => asset('storage/profile_pictures/' . $fileName)
        ]);
    }

    public function update_personal_info(Request $request)
    {
        $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:students'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'dob'=>['required','date'],
            'guardian_name'=>['required','string', 'max:255'],
            'guardian_phone'=>['required','string', 'max:255'],
        ]);

        $user = Auth::user();

        $toupdate = Student::findOrFail($user->id);
        $toupdate->fname = $request->get('fname');
        $toupdate->lname = $request->get('lname');
        $toupdate->phone = $request->get('phone');
        $toupdate->address = $request->get('address');
        $toupdate->email = $request->get('email');
        $toupdate->dob = $request->get('dob');
        $toupdate->guardian_name = $request->get('guardian_name');
        $toupdate->guardian_phone = $request->get('guardian_phone');
        $toupdate->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully!',
        ]);
    }
}
