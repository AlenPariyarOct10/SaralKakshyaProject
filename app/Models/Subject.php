<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = "subjects";
    protected $fillable =
        ['name', 'code', 'description', 'program_id', 'batch_id', 'status', 'created_by', 'standard_publisher'];

    public function subject_evaluations()
    {
        $this->hasOne(SubjectEvaluationFormat::class, 'subject_id', 'id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
