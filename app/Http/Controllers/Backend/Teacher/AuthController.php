<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
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
        return view('backend.teacher.login', compact('system_info'));
    }

    // Handle login attempt
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('teacher')->attempt($credentials)) {
            return redirect()->intended(route('teacher.dashboard'));
        }

        return redirect()->route('teacher.login')->withErrors(['email' => 'Invalid credentials']);
    }

    // Handle registration of a new teacher
    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);

        Teacher::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing the password
        ]);

        return redirect()->route('teacher.login')->with('success', 'You have successfully registered.');
    }

    // Handle logout functionality
    public function logout()
    {
        Auth::guard('teacher')->logout(); // Logout the teacher
        return redirect()->route('teacher.login');
    }
}
