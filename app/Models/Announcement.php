<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $fillable = ['title', 'department_id', 'program_id', 'type', 'content', 'attachment_id', 'pinned', 'notification', 'creator_type', 'creator_id'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'parent');
    }

}
