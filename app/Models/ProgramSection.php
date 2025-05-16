<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSection extends Model
{
    protected $fillable = [
        'program_id',
        'section_name',
        'status',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
