<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // assuming Admin is logged in

        $instituteId = Admin::find($user->id)->institute->id;

        $students = Student::where('institute_id', $instituteId)->get();

        return view('backend.admin.students.index', compact('user', 'students'));
    }

    public function index_pending_students()
    {
        $adminInstituteId = Auth::user()->institute->id;
        $user = Auth::user();

        $student = Student::whereHas('institutes', function ($query) use ($adminInstituteId) {
            $query->where('institute_id', $adminInstituteId)
                ->whereNull('institute_student.approved_at');
        })->orderBy('created_at', 'DESC')->get();

        return view('backend.admin.students.unapproved', compact('student', 'user'));
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
        //
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
