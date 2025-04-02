<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
