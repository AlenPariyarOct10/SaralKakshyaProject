<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $fillable = ['title', 'department_id', 'program_id', 'type', 'content', 'attachment_id', 'pinned', 'notification', 'creator_type', 'creator_id'];

    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments', 'id')->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs', 'id')->nullOnDelete();
            $table->string('type')->nullable();
            $table->longText('content')->nullable();
            $table->foreignId('attachment_id')->nullable()->constrained('attachments', 'id')->nullOnDelete();
            $table->boolean('pinned')->default(false);
            $table->boolean('notification')->default(false);
            $table->string('creator_type')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
