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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type', 20)->nullable();
            $table->string('action_type', 100);
            $table->text('description', 1000);
            $table->json('before_data',2000)->nullable();
            $table->json('after_data', 2000)->nullable();
            $table->string('model_type', 500)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('url', 500)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->text('user_agent', 1000)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
