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
        Schema::create('subject_evaluation_formats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->string('criteria', 255);
            $table->double('full_marks', 1000);
            $table->double('pass_marks', 1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_evaluation_formats');
    }
};
