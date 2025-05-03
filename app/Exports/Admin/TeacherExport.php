<?php

namespace App\Exports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

class TeacherExport implements FromView
{
    public function view(): View
    {
        $instituteId = Auth::user()->institute->id;

        $teachers = Teacher::select('teachers.*', 'institute_teacher.isApproved', 'institute_teacher.approvedAt')
            ->join('institute_teacher', 'teachers.id', '=', 'institute_teacher.teacher_id')
            ->where('institute_teacher.institute_id', $instituteId)
            ->orderBy('institute_teacher.created_at', 'DESC')
            ->get();

        return view('backend.admin.teachers.excel-template', compact('teachers'));
    }
}
