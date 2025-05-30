<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batches';
    protected $fillable = ['department_id', 'program_id', 'semester', 'status', 'batch'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'program_id', 'program_id')
            ->where('semester', $this->semester);
    }
}
