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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 50);
            $table->string('lname', 50);
            $table->string('email', 50)->nullable()->unique();
            $table->string('phone', 12)->nullable()->unique();
            $table->string('address', 100)->nullable();
            $table->string('gender', 10)->nullable(); // Male, Female, Other
            $table->date('dob')->nullable(); // Date of birth
            $table->string('guardian_name', 100)->nullable(); // Parent/guardian name
            $table->string('guardian_phone', 12)->nullable(); // Guardian's contact number
            $table->string('roll_number', 20)->unique()->nullable(); // Unique student roll number
            $table->string('batch', 20)->nullable(); // Class or grade
            $table->string('section', 10)->nullable(); // Section (if applicable)
            $table->string('admission_number', 20)->unique(); // Admission ID
            $table->date('admission_date')->nullable(); // Admission date
            $table->text('profile_picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status')->default(1); // 1 = Active, 0 = Inactive
            $table->rememberToken(); // For authentication
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
