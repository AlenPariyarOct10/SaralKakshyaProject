<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluationDetail extends Model
{
    protected $table = 'student_evaluation_details';
    protected $fillable = [
        'evaluation_format_id',
        'subject_id',
        'evaluated_by',
        'comment',
        'obtained_marks',
        'normalized_marks',
        'semester',
        'institute_id',
        'created_by',
        'batch_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function evaluationFormat()
    {
        return $this->belongsTo(SubjectEvaluationFormat::class, 'evaluation_format_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(Teacher::class, 'evaluated_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Teacher::class, 'created_by', 'id');
    }
}
