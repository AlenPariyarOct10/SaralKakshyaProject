<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all departments
        $departments = Department::where('institute_id', session('institute_id'))
            ->get();
        // Return the view with the departments
        return response([
            'status' => true,
            'data' => $departments,
        ]);
    }

    public function getPrograms($id)
    {
        $programs = Program::where('institute_id', session('institute_id'))
            ->where('department_id', $id)
            ->get();
        return response([
            'status' => true,
            'data' => $programs,
        ]);
    }

    public function getSubjects($id)
    {
        $programs = Subject::where('program_id', $id)
            ->get();
        return response([
            'status' => true,
            'data' => $programs,
        ]);
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
