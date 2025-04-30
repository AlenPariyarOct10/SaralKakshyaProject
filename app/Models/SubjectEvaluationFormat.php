<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectEvaluationFormat extends Model
{
    protected $table = 'subject_evaluation_formats';
    protected $fillable = ['subject_id', 'criteria', 'full_marks', 'pass_marks', 'marks_weight'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

}
