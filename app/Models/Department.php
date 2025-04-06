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
    ];

    public function program()
    {
        return $this->hasMany(Program::class);
    }

    function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
