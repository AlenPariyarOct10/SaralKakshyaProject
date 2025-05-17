<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'status',
        'description',
        'institute_id',
    ];

    public function program()
    {
        return $this->hasMany(Program::class);
    }

    public function mappings()
    {
        return $this->hasManyThrough(
            SubjectTeacherMapping::class,
            Subject::class,
            'department_id', // Foreign key on subjects table
            'subject_id',    // Foreign key on mappings table
            'id',
            'id'
        );
    }

    function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
