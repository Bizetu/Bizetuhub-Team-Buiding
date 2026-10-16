<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_evaluations', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('course_id')->nullable();
            $table->string('username')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();

            $table->string('question1')->nullable();
            $table->string('answer1')->nullable();
            $table->string('question2')->nullable();
            $table->string('answer2')->nullable();
            $table->string('question3')->nullable();
            $table->string('answer3')->nullable();
            $table->string('question4')->nullable();
            $table->string('answer4')->nullable();
            $table->string('question5')->nullable();
            $table->string('answer5')->nullable();
            $table->string('question6')->nullable();
            $table->string('answer6')->nullable();
            $table->string('question7')->nullable();
            $table->string('answer7')->nullable();
            $table->string('question8')->nullable();
            $table->string('answer8')->nullable();
            $table->string('question9')->nullable();
            $table->string('answer9')->nullable();
            $table->string('question10')->nullable();
            $table->string('answer10')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_evaluations');
    }
};
