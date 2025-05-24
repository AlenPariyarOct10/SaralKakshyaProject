<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Institute;
use App\Models\InstituteStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    protected $page_title = "Dashboard";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();

        $studentGrowth = $this->getStudentsGrowth();

        $departments = Department::where('institute_id', session('institute_id'))->get();
        $attendanceGrowth = $this->getAttendanceGrowth(session('institute_id'));
        $students = InstituteStudent::where('institute_id', session('institute_id'))
            ->where('status', 'active')
            ->get();

        $attendanceReport = $this->showAttendanceReport($user->institute->id);

        $resources = $user->institute->resources()
            ->get();

        $announcements = $user->institute->announcements()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $activityLogs = ActivityLog::where('user_type', 'admin')
        ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();


        return view('backend.admin.dashboard', compact(
            'user',
            'students',
            'studentGrowth',
            'departments',
            'resources',
            'attendanceReport',
            'attendanceGrowth',
            'announcements',
            'activityLogs'
        ));
    }

    public function getAttendanceGrowth($instituteId)
    {
        // Current week attendance
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        $currentWeekAttendance = Attendance::where('institute_id', $instituteId)
            ->where('attendee_type', 'student')
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->where('status', 'present')
            ->count();

        // Last week attendance
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        $lastWeekAttendance = Attendance::where('institute_id', $instituteId)
            ->where('attendee_type', 'student')
            ->whereBetween('date', [$lastWeekStart, $lastWeekEnd])
            ->where('status', 'present')
            ->count();

        // Calculate growth
        $growth = $currentWeekAttendance - $lastWeekAttendance;

        // Calculate percentage growth
        $growthPercentage = 0;
        if ($lastWeekAttendance > 0) {
            $growthPercentage = ($growth / $lastWeekAttendance) * 100;
        } elseif ($currentWeekAttendance > 0) {
            $growthPercentage = 100; // Infinite growth if no attendance last week
        }

        return [
            'current_week' => $currentWeekAttendance,
            'last_week' => $lastWeekAttendance,
            'growth' => $growth,
            'growth_percentage' => round($growthPercentage, 2)
        ];
    }


    public function showAttendanceReport($instituteId)
    {
        $institute = Institute::with(['students' => function($query) {
            $query->withCount(['attendances as present_days' => function($query) {
                $query->where('status', 'present');
            }])
                ->withCount(['attendances as total_days']);
        }])->findOrFail($instituteId);

        $stats = $institute->getAttendanceStats();

        return $stats;
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

    public function getStudentsGrowth()
    {
        // Current month active students
                $currentMonthStart = Carbon::now()->startOfMonth();
                $currentMonthStudents = InstituteStudent::where('institute_id', session('institute_id'))
                    ->where('status', 'active')
                    ->where('created_at', '>=', $currentMonthStart)
                    ->count();

        // Previous month active students
                $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
                $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
                $lastMonthStudents = InstituteStudent::where('institute_id', session('institute_id'))
                    ->where('status', 'active')
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                    ->count();

        // Calculate growth and percentage
        $growth = $currentMonthStudents - $lastMonthStudents;
        $growthPercentage = 0;

        if ($lastMonthStudents > 0) {
            $growthPercentage = ($growth / $lastMonthStudents) * 100;
        } else if ($currentMonthStudents > 0) {
            $growthPercentage = 100; // Infinite growth represented as 100%
        }

        $growthPercentage = round($growthPercentage, 2);

        return $growthPercentage;
    }
}
