<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $teacher = Teacher::where('id', Auth::user()->id)->first();
        $institute = Institute::where('id', session("institute_id"))->first();


        return view('backend.teacher.profile.index', compact('teacher', 'institute'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'dob' => 'required|date_format:Y-m-d',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:teachers,email,' . Auth::id(),
        ]);

        $profile = Teacher::find(Auth::id());

        if ($profile) {
            $profile->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'email' => $request->email,
                'profile_picture' => $request->profile_picture,
            ]);
        }

        return redirect()->route('teacher.profile.index')->with('success', 'Profile updated successfully');
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
        //
    }
}
