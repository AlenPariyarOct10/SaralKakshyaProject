<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;
    protected $guard = 'student';

    protected $table = 'students'; // Specify the table name if not default

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'address',
        'gender',
        'dob',
        'guardian_name',
        'guardian_phone',
        'roll_number',
        'batch_id',
        'section',
        'admission_number',
        'admission_date',
        'institute_id',
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
        'admission_date' => 'date',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the full name of the student.
     */
    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }

    public function unseenNotifications()
    {
        return $this->notifications()->whereNull('seen_at');
    }

    public function institutes()
    {
        return $this->belongsToMany(Institute::class, 'institute_student')
            ->withPivot('approved_at')
            ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'id')
            ->where('user_type', 'student');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }


    public function instituteStudents()
    {
        return $this->hasMany(InstituteStudent::class);
    }

    public function currentInstitute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
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
        return $this->belongsTo(ProgramSection::class, 'section_id', 'id');
    }

    public function studentEvaluations()
    {
        return $this->hasMany(StudentEvaluation::class);
    }



    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'attendee_id')
            ->where('attendee_type', 'student');
    }


}
