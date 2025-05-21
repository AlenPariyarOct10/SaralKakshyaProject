<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteStudent extends Model
{
    protected $table = 'institute_student';

    protected $fillable = [
        'institute_id',
        'student_id',
        'batch_id',
        'department_id',
        'program_id',
        'section_id',
        'is_approved',
        'approved_at'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function section()
    {
        return $this->belongsTo(ProgramSection::class);
    }
}
