<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\SystemSetting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        $system_info = SystemSetting::first();
        return view('backend.teacher.signup', compact('system_info'));
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

//
        $credentials = $request->only('email', 'password');
        $instituteId = $request->input('institute');

        if (Auth::guard('teacher')->attempt($credentials)) {
            $teacher = Auth::guard('teacher')->user();  // Get the authenticated teacher
            $isAssociatedWithInstitute = $teacher->institutes()->where('institutes.id', $instituteId)->exists();

            if ($isAssociatedWithInstitute) {
                return redirect()->intended(route('teacher.dashboard'));
            } else {
                Auth::guard('teacher')->logout();
                return redirect()->route('teacher.login')->withErrors(['institute' => 'You are not associated with the selected institute.'])->withInput(['email'=>$request->get('email'), 'institute'=>$instituteId]);
            }
        }

        return redirect()->route('teacher.login')->withErrors(['email' => 'Invalid credentials'])->withInput(['email'=>$request->get('email'), 'institute'=>$instituteId]);
    }


    // Handle registration of a new teacher
    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);

        $teacher = Teacher::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing the password
        ]);
        $teacher->institutes()->attach($request->institute);
        return redirect()->route('teacher.login')->with('success', 'You have successfully registered.');
    }

    // Handle logout functionality
    public function logout()
    {
        Auth::guard('teacher')->logout(); // Logout the teacher
        return redirect()->route('teacher.login');
    }
}
