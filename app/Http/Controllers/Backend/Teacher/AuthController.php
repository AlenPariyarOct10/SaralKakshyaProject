<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Institute;
use App\Models\SystemSetting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        $system_info = SystemSetting::first();
        $institutes = Institute::withoutTrashed()->get();
        return view('backend.teacher.signup', compact('system_info', 'institutes'));
    }

    // Show login form
    public function showLogin()
    {
        $system_info = SystemSetting::first();
        $institutes = Institute::all();
        return view('backend.teacher.login', compact('system_info', 'institutes'));
    }

    // Handle login attempt
    public function login(Request $request)
    {
        // Validate the incoming data
        $v = $request->validate([
            'email' => 'required|email',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $instituteId = $request->input('institute');

        if (Auth::guard('teacher')->attempt($credentials)) {
            $teacher = Auth::guard('teacher')->user();  // Get the authenticated teacher

            // Check if the teacher is associated with the institute and approved
            $isApproved = $teacher->institutes()
                ->where('institutes.id', $instituteId)
                ->wherePivot('isApproved', 1)
                ->exists();

            if ($isApproved) {
                Session::put('institute_id', $instituteId);
                return redirect()->route('teacher.dashboard');
            } else {
                Auth::guard('teacher')->logout();
                return redirect()->route('teacher.login')->withErrors([
                    'institute' => 'You are either not associated with the selected institute or not yet approved.'
                ])->withInput(['email' => $request->get('email'), 'institute' => $instituteId]);
            }
        }

        return redirect()->route('teacher.login')->withErrors([
            'email' => 'Invalid credentials'
        ])->withInput(['email' => $request->get('email'), 'institute' => $instituteId]);
    }



    // Handle registration of a new teacher
    public function register(Request $request)
    {
        // Validate Phase 1 inputs
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);

        // Create teacher and associate institute
        $teacher = Teacher::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'inactive',
        ]);

        $teacher->institutes()->attach($request->institute);

        Session::put('teacher_id', $teacher->id);

        // Redirect to Phase 2
        return redirect()->route('teacher.register.step2');
    }

    public function showRegisterStep2Form()
    {
        // Ensure teacher ID exists in session
        if (!session()->has('teacher_id')) {
            return redirect()->route('teacher.register')->with('error', 'Please complete the first step of registration.');
        }

        return view('backend.teacher.signup-step2');
    }

    public function registerStep2(Request $request)
    {
        // Ensure teacher ID exists in session
        if (!session()->has('teacher_id')) {
            return redirect()->route('teacher.register')->with('error', 'Please complete the first step of registration.');
        }

        // Validate Phase 2 inputs
        $request->validate([
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'qualification' => 'required|string|max:255',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'educational_attachment' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        // Retrieve the teacher from session
        $teacher = Teacher::find(session('teacher_id'));

        // Update teacher details
        $teacher->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'qualification' => $request->qualification,
            'profile_picture' => $request->file('profile_picture')->store('profile_pictures', 'public'),
        ]);

        // Save educational qualification attachment
        Attachment::create([
            'title' => 'Educational Qualification',
            'file_type' => $request->file('educational_attachment')->getClientOriginalExtension(),
            'parent_type' => Teacher::class,
            'parent_id' => $teacher->id,
            'path' => $request->file('educational_attachment')->store('attachments', 'public'),
        ]);

        // Clear session
        Session::forget('teacher_id');

        // Redirect to login with success message
        return redirect()->route('teacher.login')->with('success', 'Registration completed successfully. You can now log in.');
    }

    // Handle logout functionality
    public function logout()
    {
        Session::forget('institute_id');
        Auth::guard('teacher')->logout(); // Logout the teacher
        return redirect()->route('teacher.login');
    }
}
