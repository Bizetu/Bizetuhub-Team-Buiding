<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('creator_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('published')->nullable();

            $table->foreign('creator_id')
                ->references('id')
                ->on(config('twill.users_table', 'twill_users'))
                ->onDelete('cascade');

            $table->index('creator_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forums');
    }
};