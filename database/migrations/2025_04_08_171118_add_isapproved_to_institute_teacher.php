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
        Schema::table('institute_teacher', function (Blueprint $table) {
            $table->boolean('isApproved')->default(false);
            $table->timestamp('approvedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institute_teacher', function (Blueprint $table) {
            $table->dropColumn('isApproved');
        });
    }
};
