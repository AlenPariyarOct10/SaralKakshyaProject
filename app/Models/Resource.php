<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resources';
    protected $fillable = [
        'title',
        'teacher_id',
        'description',
        'type',
        'subject_id',
        'chapter_id',
        'sub_chapter_id',
        'download_count',
        'views_count',
    ];

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function subChapter()
    {
        return $this->belongsTo(Chapter::class, 'sub_chapter_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'parent');
    }

}
