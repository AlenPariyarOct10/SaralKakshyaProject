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

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'subject_id', 'id');
    }
}
