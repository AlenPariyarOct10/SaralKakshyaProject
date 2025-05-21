<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        $announcements = Announcement::query()
            ->where(function ($query) use ($student) {
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

        return view('backend.student.announcement.index', compact('announcements'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Auth::guard('student')->user();

        $announcement = Announcement::where('id', $id)
            ->where(function ($query) use ($student) {
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
            ->firstOrFail();

        return view('backend.student.announcement.show', compact('announcement'));
    }

}
