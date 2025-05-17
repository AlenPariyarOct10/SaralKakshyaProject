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

// In ClassRoutine.php model
    public function subjectTeacherMapping()
    {
        return $this->belongsTo(SubjectTeacherMapping::class, 'subject_teacher_mappings_id', 'id');

    }
}
