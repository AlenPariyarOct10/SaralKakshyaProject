<?php

namespace App\Exports\Admin;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class StudentExport implements FromView
{
    public function view(): View
    {
        $instituteId = Auth::user()->institute->id;

        $students = Student::select('students.*', 'institute_student.is_approved', 'institute_student.approved_at')
            ->join('institute_student', 'students.id', '=', 'institute_student.teacher_id')
            ->where('institute_student.institute_id', $instituteId)
            ->orderBy('institute_student.created_at', 'DESC')
            ->get();

        return view('backend.admin.student.excel-template', compact('students'));
    }
}
