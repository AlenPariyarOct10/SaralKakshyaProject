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
        Schema::create('institute_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('notes')->nullable();
            $table->string('creator_type')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->string("specific_group")->nullable();
            $table->unsignedBigInteger("specific_group_id")->nullable();
            $table->enum('status', ['holiday', 'class', 'exam', 'event'])->default('class');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_sessions');
    }
};
