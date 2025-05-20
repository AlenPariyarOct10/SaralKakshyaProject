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
        'batch',
        'section',
        'admission_number',
        'admission_date',
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







}
