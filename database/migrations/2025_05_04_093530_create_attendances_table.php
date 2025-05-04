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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('attendee_type', 50)->nullable();
            $table->unsignedBigInteger('attendee_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->date('date')->default(now());
            $table->dateTime('attended_at')->default(now());
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->string('method', 50)->default('face');
            $table->string('creator_type', 50)->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('remarks', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
