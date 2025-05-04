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
            'password_confirmation' => 'required|string|min:6',
        ]);

        $student = Student::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing the password
        ]);
        $student->institutes()->attach($request->institute);
        return redirect()->route('student.login')->with('success', 'You have successfully registered.');
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
}
