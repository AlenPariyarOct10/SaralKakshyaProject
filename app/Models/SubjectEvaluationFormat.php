<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectEvaluationFormat extends Model
{
    protected $table = 'subject_evaluation_format';
    protected $fillable = ['subject_id', 'criteria', 'full_marks', 'pass_marks'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
