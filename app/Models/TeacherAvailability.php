<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TeacherAvailability extends Model
{
    protected $table = 'teacher_availabilities';

    protected $fillable = [
        'teacher_id',
        'institute_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the teacher that owns the availability.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the institute that owns the availability.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Scope a query to only include available time slots.
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to a specific day of the week.
     */
    public function scopeOnDay(Builder $query, string $day)
    {
        return $query->where('day_of_week', ucfirst(strtolower($day)));
    }

    /**
     * Scope a query to a specific teacher.
     */
    public function scopeForTeacher(Builder $query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}
