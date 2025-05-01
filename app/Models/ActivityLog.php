<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = "activity_logs";
    protected $fillable = ['user_id', 'action_type', 'user_type', 'description', 'before_data', 'after_data', 'model_type', 'model_id', 'url', 'ip_address', 'user_agent'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id', 'id');
    }
}
