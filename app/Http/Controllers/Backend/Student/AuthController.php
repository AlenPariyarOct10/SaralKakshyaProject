<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'email' => 'required|email',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $instituteId = $request->input('institute');

        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();  // Get the authenticated teacher

            $isApproved = $student->institutes()
                ->where('institutes.id', $instituteId)
                ->wherePivot('isApproved', 1)
                ->exists();

            if ($isApproved) {
                return redirect()->intended(route('student.dashboard'));
            } else {
                Auth::guard('student')->logout();
                return redirect()->route('student.login')->withErrors([
                    'institute' => 'You are either not associated with the selected institute or not yet approved.'
                ])->withInput(['email' => $request->get('email'), 'institute' => $instituteId]);
            }
        }
        return redirect()->route('student.login')->withErrors([
            'email' => 'Invalid credentials'
        ])->withInput(['email' => $request->get('email'), 'institute' => $instituteId]);

    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('student.login');
    }
}
