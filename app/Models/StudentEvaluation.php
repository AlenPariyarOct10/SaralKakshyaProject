<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluation extends Model
{
    protected $table = 'student_evaluations';
    protected $fillable = [
        'subject_id',
        'evaluation_format_id',
        'institute_id',
        'evaluated_by',
        'comment',
        'total_obtained_marks',
        'total_normalized_marks',
        'is_finalized',
        'semester',
        'batch_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }


    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function evaluationFormat()
    {
        return $this->belongsTo(SubjectEvaluationFormat::class, 'evaluation_format_id', 'id');
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(Teacher::class, 'evaluated_by', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }



}
