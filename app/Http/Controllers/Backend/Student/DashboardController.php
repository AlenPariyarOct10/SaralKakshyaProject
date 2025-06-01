<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
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

        return view('backend.student.dashboard', compact('user', 'activityLog', 'announcements'));
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
