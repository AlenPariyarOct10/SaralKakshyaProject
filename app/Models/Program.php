<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';

    protected $fillable = [
        'name',
        'department_id',
        'total_semesters',
        'duration',
        'status',
        'description',
        'institute_id',
        'created_by',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'program_id', 'id');
    }

    public function batch()
    {
        return $this->hasMany(Batch::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections()
    {
        return $this->hasMany(ProgramSection::class, 'program_id', 'id');
    }
}
