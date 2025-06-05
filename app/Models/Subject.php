<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = "subjects";
    protected $fillable =
        ['name', 'code', 'description','credit', 'program_id', 'semester', 'max_external_marks', 'max_internal_marks','batch_id', 'status', 'created_by', 'updated_by'];

    public function subject_evaluations()
    {
        return $this->hasMany(SubjectEvaluationFormat::class, 'subject_id', 'id');
    }

    public function evaluationDetails()
    {
        return $this->hasMany(StudentEvaluationDetail::class, 'evaluation_format_id');
    }

    public function subjectTeacherMappings()
    {
        return $this->hasMany(\App\Models\SubjectTeacherMapping::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'subject_id', 'id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'subject_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'subject_id', 'id');
    }
}
