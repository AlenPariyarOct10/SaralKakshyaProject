<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Notification;
use Illuminate\Http\Request;

class AssignmentController extends Controller
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
        //
    }

    public function download($id)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();

        $assignment = Assignment::with(['subject', 'batch'])
            ->where('id', $id)
            ->firstOrFail();

        $submittedStatus = $assignment->submissions()
            ->where('student_id', auth()->user()->id)
            ->exists();

        Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\Student')
            ->where('parent_type', 'App\Models\Assignment')
            ->where('parent_id', $assignment->id)
            ->where('seen_at', null)
            ->update(['seen_at' => now(), 'read_at' => now()]);

        return view('backend.student.assignment.show', compact('assignment','user', 'submittedStatus'));
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
