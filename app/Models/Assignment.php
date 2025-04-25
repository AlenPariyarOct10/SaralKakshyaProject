<?php

namespace App\Models;

use App\Http\Controllers\Backend\Teacher\AssignmentController;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $fillable = ['title', 'full_marks', 'parent_type', 'parent_id', 'path'];
    public function attachments()
    {
        return $this->morphMany(AssignmentController::class, 'parent');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

}
