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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->string('title', 100);
            $table->string('description', 255)->nullable();
            $table->string('slug', 100)->unique();
            $table->integer('chapter_number')->nullable();
            $table->integer('level')->default(1);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->nullable();


            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
