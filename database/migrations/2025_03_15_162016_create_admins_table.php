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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 50);
            $table->string('lname', 50);
            $table->string('email', 50)->nullable()->unique();
            $table->string('phone', 12)->nullable()->unique();
            $table->string('address', 60)->nullable();
            $table->text('profile_picture', 200)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('super_admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['approved_by']);

            // Now drop the columns
            $table->dropColumn(['is_approved', 'approved_by', 'approved_at']);
        });

        // Finally, drop the admins table
        Schema::dropIfExists('admins');
    }
};
