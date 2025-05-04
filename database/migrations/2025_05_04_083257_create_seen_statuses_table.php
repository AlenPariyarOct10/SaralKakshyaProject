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
        Schema::create('seen_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('seenable_type', 100);
            $table->unsignedBigInteger('seenable_id');
            $table->string('user_type', 100);
            $table->unsignedBigInteger('user_id');

            $table->timestamp('seen_at')->nullable();
            $table->timestamps();

            $table->unique(['seenable_type', 'seenable_id', 'user_type', 'user_id'], 'seen_status_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seen_statuses');
    }
};
