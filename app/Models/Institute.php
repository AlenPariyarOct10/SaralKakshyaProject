<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
$table->id();
$table->string('name', 80);
$table->string('address', 80);
$table->string('description', 500);
$table->string('logo')->nullable();
$table->foreignId('created_by')->constrained('users')->onDelete('cascade');
$table->timestamps();
$table->softDeletes();
class Institute extends Model
{
    use SoftDeletes;
    protected $table = 'institutes';
    protected $fillable = ['name', 'address', 'description', 'logo', 'created_by'];

    public function departments()
    {
        $this->hasMany(Department::class, 'institute_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'institute_id', 'id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'institute_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }

}
