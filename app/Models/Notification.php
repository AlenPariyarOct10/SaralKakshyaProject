<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['title', 'creator_type', 'creator_id', 'url', 'visibility', 'scope_type', 'scope_id', 'subscope_type',
        'subscope_id', 'notifiable_type', 'notifiable_id', 'parent_type', 'parent_id'];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->morphTo();
    }




    public function seenStatuses()
    {
        return $this->morphMany(SeenStatus::class, 'seenable');
    }
}
