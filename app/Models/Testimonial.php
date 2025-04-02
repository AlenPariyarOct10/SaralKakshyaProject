<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected $fillable = [
        'user_name',
        'profile_picture',
        'stars',
        'designation',
        'rank',
        'status',
        'description',
    ];
}
