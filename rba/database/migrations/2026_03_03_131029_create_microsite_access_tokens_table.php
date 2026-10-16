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
        Schema::create('microsite_access_tokens', function (Blueprint $table) {
         $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('provider');
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->bigInteger('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('twill_users')->onDelete('cascade');
            $table->unique(['user_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('microsite_access_tokens');
    }
};
