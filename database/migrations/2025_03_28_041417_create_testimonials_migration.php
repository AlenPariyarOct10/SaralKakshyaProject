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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('user_name', 200);
            $table->text('profile_picture', 500);
            $table->integer('stars')->default(0);
            $table->string('designation', 250)->nullable();
            $table->integer('rank')->default(0)->nullable();
            $table->string('status')->default("active");
            $table->string('description', 500);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
