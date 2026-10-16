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
        Schema::create('training_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('twill_users')->onDelete('cascade');
            $table->foreignId('like_id')->constrained('user_licenses')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint - user can only like a license once
            $table->unique(['user_id', 'like_id', 'course_id'], 'unique_user_license_like');
            
            // Indexes for better performance
            $table->index(['like_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_likes');
    }
};