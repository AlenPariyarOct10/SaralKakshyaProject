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

}
