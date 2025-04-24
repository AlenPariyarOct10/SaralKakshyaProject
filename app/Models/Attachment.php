<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachments';
    protected $fillable = ['title', 'file_type', 'parent_type', 'parent_id', 'path'];
    public function parent()
    {
        return $this->morphTo();
    }

}
