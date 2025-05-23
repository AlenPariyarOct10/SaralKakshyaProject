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
        Schema::create('student_evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('student_evaluations')->onDelete('cascade');
            $table->foreignId('evaluation_format_id')->constrained('subject_evaluation_formats')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('evaluated_by')->constrained('teachers')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->decimal('obtained_marks', 8, 2);
            $table->decimal('normalized_marks', 8, 2);
            $table->string('semester', 20);
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluation_details');
    }
};
