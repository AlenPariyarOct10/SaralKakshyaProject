<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = ['attendee_type', 'institute_id','attendee_id', 'subject_id', 'date','attended_at', 'status', 'method', 'creator_type', 'creator_id', 'is_verified', 'remarks'];


    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'attendee_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'attendee_id')->where('attendee_type', 'teacher');
    }
}
