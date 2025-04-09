<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends Model
{
    use SoftDeletes;
    protected $table = 'institutes';
    protected $fillable = ['name', 'address', 'email','description', 'logo', 'created_by'];

    public function departments()
    {
        $this->hasMany(Department::class, 'institute_id', 'id');
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

}
