<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteSession extends Model
{
    protected $fillable = [
        'institute_id',
        'date',
        'start_time',
        'end_time',
        'notes',
        'status',
        'notes',
        'creator_type',
        'creator_id',
        'specific_group',
        'specific_group_id',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

}
