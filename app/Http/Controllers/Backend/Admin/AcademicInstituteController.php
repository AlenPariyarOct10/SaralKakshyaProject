<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Institute;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AcademicInstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($token)
    {
        $system_info = SystemSetting::first();

        $admin_id = Crypt::decryptString($token);
        $user = Admin::findOrFail($admin_id);

        if($user)
        {
            return view('backend.admin.create-institute', compact('system_info', 'user'));
        }else{
            return redirect()->route('admin.register');
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $system_info = SystemSetting::first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:institutes,email',
            'description' => 'required|string|max:1000',
            'admin_id' => 'required|exists:admins,id', // ensure it's coming from the hidden input
        ]);

        $validated['created_by'] = $validated['admin_id'];

        $institute = Institute::create($validated);

        if ($institute) {
            return redirect()->route('admin.login')->with('success', 'Account has been created. Please login to continue.');
        } else {
            return redirect()->route('admin.login')->with('error', 'Unable to create account.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $institute = Institute::findOrFail($id);
        $institute->delete();

        return redirect()->back()->with('success', 'Institute soft-deleted successfully.');
    }

}
