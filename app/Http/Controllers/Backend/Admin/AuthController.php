<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Institute;
use App\Models\Student;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegister()
    {
        $system_info = SystemSetting::first();

        return view('backend.admin.signup', compact('system_info'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);

        $admin = Admin::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($admin) {
            $token = Crypt::encryptString($admin->id);
            return redirect()->route('admin.register.institute.create',['token' => $token]);
        } else {
            return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }


    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $system_info = SystemSetting::first();
        $institutes = Institute::all();
        return view('backend.admin.login', compact('system_info', 'institutes'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'institute' => 'required|exists:institutes,id',
            'password' => 'required|min:6',
        ]);

        $institute = Institute::find($request->institute);

        $admin = Admin::where('email', $request->email)->first();



        if ($admin && $admin->id == $institute->created_by && Hash::check($request->password, $admin->password)) {

            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or institute'])->withInput();
    }



    public function logout()
    {
        Auth::guard('admin')->logout(); // Logout the admin


        return redirect()->route('admin.login');
    }

}
