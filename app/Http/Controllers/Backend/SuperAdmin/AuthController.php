<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // Show login form
    public function showLogin()
    {
        $system_info = SystemSetting::first();
        return view('backend.superadmin.login', compact('system_info'));
    }

    // Handle login attempt
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('super_admin')->attempt($credentials)) {
            return redirect()->intended(route('superadmin.index'));
        }

        return redirect()->route('superadmin.login')->withErrors(['email' => 'User not found. Please try again.']);
    }

    public function logout()
    {
        Auth::guard('super_admin')->logout();
        return redirect()->route('superadmin.login');
    }
}
