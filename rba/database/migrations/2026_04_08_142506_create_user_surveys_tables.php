<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_surveys', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->integer('position')->unsigned()->nullable();

            $table->unsignedBigInteger('survey_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('job_role_id')->nullable();
            $table->string('email')->nullable();
            $table->string('name')->nullable();

            $table->string('question_1')->nullable();
            $table->json('answer_1')->nullable();
            $table->string('question_2')->nullable();
            $table->json('answer_2')->nullable();
            $table->string('question_3')->nullable();
            $table->json('answer_3')->nullable();
            $table->string('question_4')->nullable();
            $table->json('answer_4')->nullable();
            $table->string('question_5')->nullable();
            $table->json('answer_5')->nullable();
            $table->string('question_6')->nullable();
            $table->json('answer_6')->nullable();
            $table->string('question_7')->nullable();
            $table->json('answer_7')->nullable();
            $table->string('question_8')->nullable();
            $table->json('answer_8')->nullable();
            $table->string('question_9')->nullable();
            $table->json('answer_9')->nullable();
            $table->string('question_10')->nullable();
            $table->json('answer_10')->nullable();
            $table->string('question_11')->nullable();
            $table->json('answer_11')->nullable();
            $table->string('question_12')->nullable();
            $table->json('answer_12')->nullable();
            $table->string('question_13')->nullable();
            $table->json('answer_13')->nullable();
            $table->string('question_14')->nullable();
            $table->json('answer_14')->nullable();
            $table->string('question_15')->nullable();
            $table->json('answer_15')->nullable();
            $table->string('question_16')->nullable();
            $table->json('answer_16')->nullable();
            $table->string('question_17')->nullable();
            $table->json('answer_17')->nullable();
            $table->string('question_18')->nullable();
            $table->json('answer_18')->nullable();
            $table->string('question_19')->nullable();
            $table->json('answer_19')->nullable();
            $table->string('question_20')->nullable();
            $table->json('answer_20')->nullable();
            $table->string('question_21')->nullable();
            $table->json('answer_21')->nullable();
            $table->string('question_22')->nullable();
            $table->json('answer_22')->nullable();
            $table->string('question_23')->nullable();
            $table->json('answer_23')->nullable();
            $table->string('question_24')->nullable();
            $table->json('answer_24')->nullable();
            $table->string('question_25')->nullable();
            $table->json('answer_25')->nullable();
            $table->string('question_26')->nullable();
            $table->json('answer_26')->nullable();
            $table->string('question_27')->nullable();
            $table->json('answer_27')->nullable();
            $table->string('question_28')->nullable();
            $table->json('answer_28')->nullable();
            $table->string('question_29')->nullable();
            $table->json('answer_29')->nullable();
            $table->string('question_30')->nullable();
            $table->json('answer_30')->nullable();
        });

        Schema::create('user_survey_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'user_survey');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_survey_revisions');
        Schema::dropIfExists('user_surveys');
    }
};
