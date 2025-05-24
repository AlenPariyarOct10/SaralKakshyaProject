<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;

class Teacher extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;
    protected $guard = 'teacher';


    protected $table = 'teachers'; // Define table name explicitly

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'address',
        'gender',
        'dob',
        'qualification',
        'profile_picture',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the full name of the teacher.
     */
    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function institutes()
    {
        return $this->belongsToMany(Institute::class, 'institute_teacher')->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'id')
            ->where('user_type', 'teacher');
    }

    public function seenStatuses()
    {
        return $this->morphMany(SeenStatus::class, 'user');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher_mappings')
            ->withTimestamps();
    }

    public function subjectTeacherMappings()
    {
        return $this->hasMany(SubjectTeacherMapping::class);
    }


}
