<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassRoutine;
use App\Models\InstituteSession;
use App\Models\InstituteStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('student')->user();
        $instituteId = session('institute_id');

        $assignments = Assignment::with(['subject', 'batch', 'assignmentSubmissions', 'attachments'])
            ->where('batch_id', $user->batch_id)
            ->where('status', 'active')
            ->orderBy('due_date', 'asc')
            ->get();

        $rate = $this->monthlyAttendanceRate();


        $activityLog = ActivityLog::where('user_id', $user->id)
            ->where('user_type', 'student')
            ->orderBy('id', 'desc')->take(8)->get();

        $announcements = Announcement::query()
            ->where(function ($query) use ($user) {
                $query->whereNull('institute_id')
                    ->orWhere('institute_id', session('institute_id'));
            })
            ->where(function ($query) {
                $query->whereNull('department_id')
                    ->orWhere('department_id', session('department_id'));
            })
            ->where(function ($query) {
                $query->whereNull('program_id')
                    ->orWhere('program_id', session('program_id'));
            })
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('backend.student.dashboard', compact('assignments','rate','user', 'activityLog', 'announcements'));
    }

    public function monthlyAttendanceRate()
    {
        $user = Auth::user();
        $studentId = $user->id;

        $instituteStudent = InstituteStudent::where('student_id', $studentId)
            ->where('is_approved', true)
            ->first();

        if (!$instituteStudent) {
            return 0;
        }

        $instituteId = $instituteStudent->institute_id;
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        // Get valid class session dates within the current month up to today
        $classSessions = InstituteSession::where('institute_id', $instituteId)
            ->where('status', 'class')
            ->whereBetween('date', [$startOfMonth, $today])
            ->pluck('date')
            ->toArray();

        $totalClassDays = count($classSessions);

        if ($totalClassDays === 0) {
            return 0;
        }

        // Get attendance records for present or late days
        $presentDays = Attendance::where('attendee_id', $studentId)
            ->where('attendee_type', 'student')
            ->where('institute_id', $instituteId)
            ->whereBetween('date', [$startOfMonth, $today])
            ->whereIn('status', ['present', 'late'])
            ->whereIn('date', $classSessions)
            ->count();

        // Calculate percentage
        $attendancePercentage = ($presentDays / $totalClassDays) * 100;

        return round($attendancePercentage, 2); // e.g. 85.75%
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
