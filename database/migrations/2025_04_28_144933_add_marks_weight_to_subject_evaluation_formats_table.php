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
            $table->double('marks_weight')->after('pass_marks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_evaluation_formats', function (Blueprint $table) {
            //
        });
    }
};
