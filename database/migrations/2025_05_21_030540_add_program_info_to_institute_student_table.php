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
        Schema::table('institute_student', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->after('student_id')->nullable();
            $table->unsignedBigInteger('program_id')->after('department_id')->nullable();
            $table->unsignedBigInteger('section_id')->after('program_id')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('program_sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institute_student', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['program_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn('department_id');
            $table->dropColumn('program_id');
            $table->dropColumn('section_id');
        });
    }
};
