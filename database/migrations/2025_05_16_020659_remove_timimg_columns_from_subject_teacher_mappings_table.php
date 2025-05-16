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
            $table->dropColumn(['start_time', 'end_time', 'sections']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_teacher_mappings', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('assigned_at');
            $table->time('end_time')->nullable()->after('start_time');
            $table->json('sections')->nullable()->after('end_time');
        });
    }
};
