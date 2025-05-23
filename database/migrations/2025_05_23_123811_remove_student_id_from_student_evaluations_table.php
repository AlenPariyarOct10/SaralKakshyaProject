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
        Schema::table('student_evaluations', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['student_id']);

            // Then, drop the unique constraint (if it includes student_id)
            $table->dropUnique('student_evaluation_unique_constraint');

            // Now, drop the column
            $table->dropColumn('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_evaluations', function (Blueprint $table) {
            Schema::table('student_evaluations', function (Blueprint $table) {
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

                // Re-add the unique constraint
                $table->unique(['student_id', 'subject_id', 'evaluation_format_id', 'semester', 'batch'],
                    'student_evaluation_unique_constraint');
            });
        });
    }
};
