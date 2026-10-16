<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forum_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('blocked_by');
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('published')->nullable();

            $table->foreign('forum_id')->references('id')->on('forums')->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on(config('twill.users_table', 'twill_users'))
                ->onDelete('cascade');
            $table->foreign('blocked_by')
                ->references('id')
                ->on(config('twill.users_table', 'twill_users'))
                ->onDelete('cascade');

            $table->unique(['forum_id', 'user_id']);
            $table->index('forum_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_blocks');
    }
};