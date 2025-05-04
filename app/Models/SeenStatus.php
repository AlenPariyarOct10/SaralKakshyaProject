<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeenStatus extends Model
{
    protected $table = 'seen_statuses';
    protected $fillable = [
        'seenable_type',
        'seenable_id',
        'user_type',
        'user_id',
        'seen_at'
    ];

    public function seenable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphTo();
    }
}
