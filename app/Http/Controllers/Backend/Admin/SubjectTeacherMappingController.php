<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectTeacherMapping;
use Illuminate\Http\Request;

class SubjectTeacherMappingController extends Controller
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
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'sections' => 'required|array',
            'sections.*' => 'required|exists:program_sections,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Store the subject-teacher mapping
        $mapping = new SubjectTeacherMapping();
        $mapping->subject_id = $request->subject_id;
        $mapping->teacher_id = $request->teacher_id;
        $mapping->sections = json_encode($request->sections);
        $mapping->start_time = $request->start_time;
        $mapping->end_time = $request->end_time;
        $mapping->assigned_by = auth()->user()->id;
        $mapping->assigned_at = now();
        $mapping->save();

        return response()->json(['message' => 'Subject-Teacher mapping created successfully.']);
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
