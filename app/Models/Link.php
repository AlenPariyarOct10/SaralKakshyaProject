<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'resource_id',
        'link_type',
        'url',
        'title',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
