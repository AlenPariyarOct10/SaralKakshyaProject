<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Institute;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        $institutes = Institute::all();
        $teachers = Teacher::all();
        $students = Student::all();

       //Calculating growth of students
        $currentMonthStudents = Student::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $previousMonthStudents = Student::whereMonth('created_at', now()->month - 1)
            ->whereYear('created_at', now()->subMonth()->year)->count();

        $students->growth = self::calculateGrowth($previousMonthStudents, $currentMonthStudents);

        //Calculating growth of teachers
        $currentMonthTeachers = Teacher::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $previousMonthTeachers = Teacher::whereMonth('created_at', now()->month - 1)
            ->whereYear('created_at', now()->subMonth()->year)->count();

        $teachers->growth = self::calculateGrowth($previousMonthTeachers, $currentMonthTeachers);



        return view('backend.superadmin.dashboard', compact('admins','students', 'teachers','institutes'));
    }

    public static function calculateGrowth($previousMonthData, $currentMonthData)
    {
        $growth = 0;
        if ($previousMonthData > 0) {
            $growth = (($currentMonthData - $previousMonthData) / $previousMonthData) * 100;
        } elseif ($currentMonthData > 0) {
            $growth = 100;
        }

        return $growth;
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
