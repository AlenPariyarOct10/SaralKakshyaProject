<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoutine extends Model
{
    protected $fillable = [
        'subject_teacher_mappings_id',
        'day',
        'start_time',
        'end_time',
        'notes',
    ];

    public function subjectTeacherMapping()
    {
        return $this->belongsTo(SubjectTeacherMapping::class);
    }
}
