<?php

namespace App\Http\Controllers\Backend\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Program;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('teacher')->user();
        $teacherId = $user->id;

        $programs = Program::whereHas('subjects.subjectTeacherMappings', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->get();

        $programIds = $programs->pluck('id')->toArray();
        $totalSubjects = $this->totalSubjects()->count();
        $totalAssignments = $user->assignments()->count();
        $totalResources = Resource::where('teacher_id', $user->id)->count();
        $activityLogs = $user->activityLogs()->orderBy('created_at', 'desc')->take(5)->get();

        // Filter announcements by these department IDs
        $announcements = Announcement::where('institute_id', session()->get('institute_id'))
            ->whereIn('program_id', $programIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.teacher.dashboard', compact('activityLogs','user', 'totalAssignments','announcements', 'totalSubjects', 'totalResources'));
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










//    DASHBOARD
    public function totalSubjects()
    {
        $user = Auth::guard('teacher')->user();
        $teacherId = $user->id;

        $subjects = $user->subjectTeacherMappings()->with('subject')->get()->pluck('subject');


        return $subjects;
    }

}
