<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table = 'chapters';
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'slug',
        'chapter_number',
        'level',
        'parent_id',
        'order'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function children()
    {
        return $this->hasMany(Chapter::class, 'parent_id')->orderBy('order');
    }
}
