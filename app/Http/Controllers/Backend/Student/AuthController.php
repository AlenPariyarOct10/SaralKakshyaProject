<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Institute;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegister()
    {
        $system_info = SystemSetting::first();
        $institutes = Institute::all();
        return view('backend.student.signup', compact('system_info', 'institutes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255|min:3',
            'lname' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:students',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Temporarily store the basic details in the session
        $request->session()->put('student_registration', [
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'institute' => $request->institute,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('student.complete-profile')->with('success', 'Basic registration successful! Please complete your profile.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }
        $institutes = Institute::all();

        $system_info = SystemSetting::first();
        return view('backend.student.login', compact('system_info', 'institutes'));
    }

    public function login(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|string|min:8',
        ]);

        // Attempt authentication
        if (!Auth::guard('student')->attempt($request->only('email', 'password'))) {

            return back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput($request->only('email', 'institute'));
        }

        $student = Auth::guard('student')->user();

        // Check account status
        if (!$student->status) {
            Auth::guard('student')->logout();
            return back()
                ->with('error', 'Your account is not activated. Please contact support.')
                ->withInput($request->only('email', 'institute'));
        }

        // Check institute approval
        $isApproved = $student->institutes()
            ->where('institutes.id', $validated['institute'])
            ->wherePivot('is_approved', true)
            ->exists();

        if (!$isApproved) {
            Auth::guard('student')->logout();
            return back()
                ->withErrors([
                    'institute' => 'You are either not associated with the selected institute or not yet approved.'
                ])
                ->withInput($request->only('email', 'institute'));
        }

        Student::where('id', $student->id)->update(['institute_id' => $validated['institute']]);

        // Regenerate session for security
        $request->session()->regenerate();

        // Log login activity
        ActivityLog::create([
            'user_id'     => $student->id,
            'user_type'   => 'student',
            'action_type' => 'login',
            'description' => 'Student logged in',
            'model_type'  => get_class($student),
            'model_id'    => $student->id,
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->intended(route('student.dashboard'));
    }

    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id'     => Auth::guard('student')->id(),
            'user_type'   => 'student',
            'action_type' => 'logout',
            'description' => 'Student logged out',
            'model_type'  => get_class(Auth::guard('student')->user()),
            'model_id'    => Auth::guard('student')->id(),
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        Auth::guard('student')->logout();

        return redirect()->route('student.login');
    }

    public function showCompleteProfile(Request $request)
    {
        // Check if basic registration details are in the session
        if (!$request->session()->has('student_registration')) {
            return redirect()->route('student.register')->with('error', 'Please complete the basic registration first.');
        }

        $studentData = $request->session()->get('student_registration');
        return view('backend.student.signup-phase2', compact('studentData'));
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:15',
            'roll_number' => 'required|string|max:20|unique:students',
            'batch' => 'required|string|max:50',
            'section' => 'required|string|max:10',
            'admission_date' => 'required|date',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        // Retrieve basic registration details from the session
        $basicDetails = $request->session()->get('student_registration');

        if (!$basicDetails) {
            return redirect()->route('student.register')->with('error', 'Session expired. Please register again.');
        }

        // Create the student account
        $student = Student::create(array_merge($basicDetails, [
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'roll_number' => $request->roll_number,
            'batch' => $request->batch,
            'section' => $request->section,
            'admission_date' => $request->admission_date,
            'profile_picture' => $request->file('profile_picture') ? $request->file('profile_picture')->store('profile_pictures', 'public') : null,
            'status' => true, // Mark as active
            'institute_id' => $request->institute, // Mark as active
        ]));

        // Attach the student to the selected institute
        $student->institutes()->attach($basicDetails['institute']);

        // Log the profile completion activity
        ActivityLog::create([
            'user_id'     => $student->id,
            'user_type'   => 'student',
            'action_type' => 'complete_profile',
            'description' => 'Student completed their profile (Phase 2)',
            'model_type'  => get_class($student),
            'model_id'    => $student->id,
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        // Clear the session data after successful account creation
        $request->session()->forget('student_registration');

        return redirect()->route('student.login')->with('success', 'Profile completed successfully!');
    }
}
