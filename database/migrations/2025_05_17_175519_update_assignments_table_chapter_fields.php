<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('chapter_name');
            $table->unsignedBigInteger('chapter_id')->after('title')->nullable();
            $table->unsignedBigInteger('sub_chapter_id')->after('chapter_id')->nullable();
            $table->time('due_time')->after('due_date')->nullable();
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->foreign('sub_chapter_id')->references('id')->on('chapters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('chapter_name')->after('title')->nullable();
            $table->dropForeign(['chapter_id']);
            $table->dropForeign(['sub_chapter_id']);
            $table->dropColumn('chapter_id');
            $table->dropColumn('sub_chapter_id');
            $table->dropColumn('due_time');
        });
    }
};
