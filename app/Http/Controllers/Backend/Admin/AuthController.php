<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $system_info = SystemSetting::first();
        return view('backend.admin.login', compact('system_info'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            // Debug: Check if authentication is successful
            if (Auth::guard('admin')->user()) {
                return redirect()->route('admin.dashboard');
            }

            return back()->withErrors(['email' => 'Authentication failed, please try again']);
        }

        return redirect()->route('admin.login')->withErrors(['email' => 'Invalid credentials']);
    }


    public function logout()
    {
        Auth::guard('admin')->logout(); // Logout the admin


        return redirect()->route('admin.login');
    }

}
