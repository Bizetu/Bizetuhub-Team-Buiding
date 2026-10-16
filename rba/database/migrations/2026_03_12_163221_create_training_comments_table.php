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
        Schema::create('training_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('twill_users')->onDelete('cascade');
            $table->foreignId('license_id')->constrained('user_licenses')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('parent_comment_id')->nullable()->constrained('training_comments')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['license_id', 'course_id']);
            $table->index('parent_comment_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_comments');
    }
};