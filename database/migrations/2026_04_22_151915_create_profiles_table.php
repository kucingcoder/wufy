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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('avatar')->nullable();
            $table->text('description')->nullable();
            $table->string('job_title');
            $table->string('city');
            $table->string('province');
            $table->string('phone');
            $table->string('email');
            $table->json('links')->nullable(); // For list of links (logo, title, link)
            $table->string('cv_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
