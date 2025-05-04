<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = ['attendee_type', 'attendee_id', 'subject_id', 'date','attended_at', 'status', 'method', 'creator_type', 'creator_id', 'is_verified', 'remarks'];
}
