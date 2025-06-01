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
        Schema::table('subject_evaluation_formats', function (Blueprint $table) {
            $table->unsignedBigInteger('institute_id')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->unsignedBigInteger('semester')->nullable();

            $table->foreign('institute_id')->references('id')->on('institutes')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_evaluation_formats', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropForeign(['program_id']);
            $table->dropColumn('institute_id');
            $table->dropColumn('program_id');
            $table->dropColumn('semester');
        });
    }
};
