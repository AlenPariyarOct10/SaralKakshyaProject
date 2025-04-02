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
    ];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
