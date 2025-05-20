<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        $system_info = SystemSetting::first();
        $institutes = Institute::all();
        return view('backend.admin.auth.forgot-password', compact('system_info', 'institutes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'institute' => 'required|exists:institutes,id',
        ]);

        $admin = \App\Models\Admin::withTrashed()->where('email', $request->email)->first();
        $institute = Institute::find($request->institute);

        // Check if admin exists
        if (!$admin) {
            return back()->withErrors(['email' => 'No admin found with this email.'])->withInput();
        }

        // Check if admin is soft-deleted
        if ($admin->trashed()) {
            return back()->withErrors(['email' => 'Your account has been deleted by super-admin.'])->withInput();
        }

        // Check if admin is associated with the selected institute
        if ($admin->id !== $institute->created_by) {
            return back()->withErrors(['email' => 'Admin does not belong to the selected institute.'])->withInput();
        }

        // Attempt to send reset link
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link has been sent to your email address.')
            : back()->withErrors(['email' => __($status)])->withInput();
    }
}
