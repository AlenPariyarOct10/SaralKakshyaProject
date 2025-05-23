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
        Schema::table('student_evaluation_details', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['evaluation_id']);

            // Then drop the column
            $table->dropColumn('evaluation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_evaluation_details', function (Blueprint $table) {
            $table->foreignId('evaluation_id')
                ->constrained('student_evaluations')
                ->onDelete('cascade');
        });
    }
};
