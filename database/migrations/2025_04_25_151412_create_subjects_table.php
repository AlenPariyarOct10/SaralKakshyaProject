<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 255)->unique();
            $table->double('credit', 255)->unique();
            $table->string('description', 255)->nullable();
            $table->foreignId('program_id')->constrained('programs', 'id')->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained('batches', 'id')->cascadeOnDelete();
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
