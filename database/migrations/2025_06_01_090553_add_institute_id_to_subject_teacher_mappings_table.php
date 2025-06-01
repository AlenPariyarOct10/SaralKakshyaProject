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
        Schema::table('subject_teacher_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('institute_id')->after('id');

            $table->foreign('institute_id')->references('id')->on('institutes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_teacher_mappings', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn('institute_id');
        });
    }
};
