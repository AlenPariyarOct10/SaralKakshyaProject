<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class SuperAdmin extends Authenticatable implements CanResetPasswordContract
{
    use \Illuminate\Auth\Passwords\CanResetPassword;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Illuminate\Notifications\Notifiable;

    protected $guard = 'super_admin';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'super_admins';
    protected $fillable = ['fname', 'lname', 'email', 'password', 'profile_picture'];

    public function seenStatuses()
    {
        return $this->morphMany(SeenStatus::class, 'user');
    }
}
