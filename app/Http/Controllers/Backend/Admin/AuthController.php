<?php

namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Institute;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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

        ActivityLog::create([
            'user_id'     => $admin->id,
            'user_type'   => 'admin',
            'action_type' => 'register',
            'description' => 'Admin registered an account',
            'model_type'  => get_class($admin),
            'model_id'    => $admin->id,
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        if ($admin) {
            $token = Crypt::encryptString($admin->id);
            return redirect()->route('admin.register.institute.create',['token' => $token])->withInput();
        } else {
            return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function showLogin()
    {
       $admins = DB::table('admins')
            ->whereNotIn('id', function ($query) {
                $query->select('created_by')->from('institutes');
            })->delete();

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
        $admin = Admin::withTrashed()->where('email', $request->email)->first(); // Include soft-deleted users

        if ($admin && $admin->trashed()) {
            return back()->withErrors(['email' => 'Your account has been deleted by super-admin.'])->withInput();
        }

        $credentials = $request->only('email', 'password');
        if ($admin && $admin->id == $institute->created_by && Auth::guard('admin')->attempt($credentials)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or institute'])->withInput();
    }

    public function logout(Request $request)
    {
        // Log registration activity
        ActivityLog::create([
            'user_id'     => Auth::guard('admin')->id(),
            'user_type'   => 'admin',
            'action_type' => 'logout',
            'description' => 'Admin logged out',
            'model_type'  => get_class(Auth::guard('admin')->user()),
            'model_id'    => Auth::guard('admin')->id(),
            'url'         => $request->fullUrl(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);
        session()->forget('institute_id');

        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}
