<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batches';
    protected $fillable = ['department_id', 'institute_id','program_id', 'semester', 'status', 'batch', 'start_date', 'end_date'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'program_id', 'program_id')
            ->where('semester', $this->semester);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
