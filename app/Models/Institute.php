<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends Model
{
    use SoftDeletes;
    protected $table = 'institutes';
    protected $fillable = ['name', 'address', 'email','description', 'logo', 'created_by', 'deleted_at'];

    protected $casts = ['deleted_at' => 'datetime'];

    public function batches()
    {
        return $this->hasMany(Batch::class, 'institute_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'institute_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'institute_student');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'institute_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function sessions()
    {
        return $this->hasMany(InstituteSession::class);
    }

    public function resources()
    {
        return Resource::whereHas('teacher', function($query) {
            $query->whereHas('institutes', function($query) {
                $query->where('institutes.id', $this->id);
            });
        });
    }

// In Institute.php
    public function studentAttendances()
    {
        return $this->hasManyThrough(
            Attendance::class,
            Student::class,
            'institute_id',
            'attendee_id',
            'id',
            'id'
        )->where('attendances.attendee_type', 'student');
    }

    public function getAttendanceStats()
    {
        $stats = [
            'total_students' => $this->students()->count(),
            'records' => [],
            'average_rate' => 0
        ];

        $attendanceData = $this->studentAttendances()
            ->selectRaw('
            attendee_id,
            COUNT(*) as total_days,
            SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present_days
        ')
            ->groupBy('attendee_id')
            ->get();

        if ($attendanceData->isNotEmpty()) {
            $stats['records'] = $attendanceData->map(function($item) {
                $item->attendance_rate = $item->total_days > 0
                    ? round(($item->present_days / $item->total_days) * 100, 2)
                    : 0;
                return $item;
            });

            $totalPresent = $stats['records']->sum('present_days');
            $totalPossible = $stats['records']->sum('total_days');
            $stats['average_rate'] = $totalPossible > 0
                ? round(($totalPresent / $totalPossible) * 100, 2)
                : 0;
        }

        return $stats;
    }

}
