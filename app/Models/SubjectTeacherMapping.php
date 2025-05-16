<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacherMapping extends Model
{
    protected $table = 'subject_teacher_mappings';
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'assigned_by',
        'assigned_at',
        'start_time',
        'end_time'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(Admin::class, 'assigned_by');
    }

}
