<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $fillable = [
        'teacher_id',
        'batch_id',
        'subject_id',
        'title',
        'description',
        'assigned_date',
        'due_date',
        'due_time',
        'status',
        'full_marks',
        'semester',
        'chapter_id',
        'sub_chapter_id',

    ];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'parent');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function subChapter()
    {
        return $this->belongsTo(Chapter::class, 'sub_chapter_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignemntSubmission::class);
    }

    public function getStatusAttribute($value)
    {
        return $value === 'active' ? 'Active' : ($value === 'closed' ? 'Closed' : 'Draft');
    }

    public function getAssignedDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    public function getDueDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
