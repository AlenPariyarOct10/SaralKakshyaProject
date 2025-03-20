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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 50);
            $table->string('lname', 50);
            $table->string('email', 50)->nullable()->unique();
            $table->string('phone', 12)->nullable()->unique();
            $table->string('address', 100)->nullable();
            $table->string('gender', 10)->nullable(); // Male, Female, Other
            $table->date('dob')->nullable(); // Date of birth
            $table->string('qualification', 100)->nullable(); // Academic qualification
            $table->string('subject', 100)->nullable(); // Main subject of expertise
            $table->text('profile_picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status')->default(1); // 1 = Active, 0 = Inactive
            $table->rememberToken(); // For authentication purposes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
