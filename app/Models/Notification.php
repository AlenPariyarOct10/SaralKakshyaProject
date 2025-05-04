<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('creator_type')->nullable();
    $table->unsignedBigInteger('creator_id')->nullable();
    $table->string('url')->nullable();
    $table->enum('visibility', ['all', 'students', 'teachers'])->default('all');
    $table->string('scope_type')->nullable();
    $table->unsignedBigInteger('scope_id')->nullable();
    $table->string('subscope_type')->nullable();
    $table->unsignedBigInteger('subscope_id')->nullable();
    $table->timestamps();
});
class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['title', 'creator_type', 'creator_id', 'url', 'visibility', 'scope_type', 'scope_id', 'subscope_type', 'subscope_id'];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
