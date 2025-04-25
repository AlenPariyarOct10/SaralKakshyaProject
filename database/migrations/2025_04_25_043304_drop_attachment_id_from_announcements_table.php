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
        Schema::table('announcements', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['attachment_id']);
            // Then drop the column
            $table->dropColumn('attachment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('attachment_id')
                ->nullable()
                ->constrained('attachments', 'id')
                ->nullOnDelete();
        });
    }
};
