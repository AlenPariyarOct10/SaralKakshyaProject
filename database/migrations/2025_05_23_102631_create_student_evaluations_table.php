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
        Schema::create('student_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('evaluation_format_id')->constrained('subject_evaluation_formats')->onDelete('cascade');
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->foreignId('evaluated_by')->constrained('teachers')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->decimal('total_obtained_marks', 8, 2);
            $table->decimal('total_normalized_marks', 8, 2);
            $table->boolean('is_finalized')->default(false);
            $table->string('semester', 20);
            $table->string('batch', 20);
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'evaluation_format_id', 'semester', 'batch'],
                'student_evaluation_unique_constraint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluations');
    }
};
