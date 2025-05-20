<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();

            $table->string("title", 100);
            $table->unsignedBigInteger("teacher_id");
            $table->string("description", 255)->nullable();
            $table->string("type", 50); // Could be 'video', 'pdf', etc.

            $table->unsignedBigInteger("subject_id");
            $table->unsignedBigInteger("chapter_id")->nullable();
            $table->unsignedBigInteger("sub_chapter_id")->nullable();

            $table->unsignedInteger("download_count")->default(0);
            $table->unsignedInteger("views_count")->default(0);


            $table->timestamps();

            $table->foreign("teacher_id")->references("id")->on("teachers")->onDelete("cascade");
            $table->foreign("subject_id")->references("id")->on("subjects")->onDelete("cascade");
            $table->foreign("chapter_id")->references("id")->on("chapters")->onDelete("set null");
            $table->foreign("sub_chapter_id")->references("id")->on("chapters")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
