<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('due_dates', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('title', 200)->nullable();
            $table->String('course_id')->nullable();
            $table->string('company_id')->nullable();
            $table->unsignedBigInteger('job_role_id')->nullable();
            $table->foreign('job_role_id')->references('id')->on('job_roles')->onDelete('set null');
            $table->dateTime('due_date')->nullable();            
            $table->integer('position')->unsigned()->nullable();
            $table->timestamp('publish_start_date')->nullable();
        });

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('user_id')->nullable();
            $table->string('course_id')->nullable();
            $table->string('calendar_option')->nullable(); // Google Calendar event ID
            $table->string('event_id')->nullable(); // Google Calendar event ID
            $table->string('event_link')->nullable();
        });

        Schema::create('calendar_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar_access_tokens');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('due_dates');
    }
};
