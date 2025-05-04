<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    protected $table = 'super_admins';
    protected $fillable = ['fname', 'lname', 'email', 'password', 'profile_picture'];

    public function seenStatuses()
    {
        return $this->morphMany(SeenStatus::class, 'user');
    }
}
