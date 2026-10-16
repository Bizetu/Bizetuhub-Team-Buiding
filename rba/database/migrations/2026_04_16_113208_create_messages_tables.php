<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            // feel free to modify the name of this column, but title is supported by default (you would need to specify the name of the column Twill should consider as your "title" column in your module controller if you change it)
            $table->string('title', 200)->nullable();
            // your generated model and form include a description field, to get you started, but feel free to get rid of it if you don't need it
            $table->string('company_id')->nullable();

            $table->string('message_type')->nullable();

            $table->json('user_ids')->nullable();

            $table->json('target_admins')->nullable();

            $table->json('job_role_ids')->nullable();

            $table->json('department_ids')->nullable();

            $table->json('branch_ids')->nullable();

            $table->json('audience_ids')->nullable();

            $table->json('course_ids')->nullable();

            $table->boolean('is_recurrent')->default(false);

            $table->integer('recurrent_frequency')->nullable();

            $table->timestamp('send_date')->nullable();

            $table->boolean('sent_status')->default(false);

            $table->timestamp('next_run')->nullable();

            $table->integer('position')->unsigned()->nullable();
            
            // add those 2 columns to enable publication timeframe fields (you can use publish_start_date only if you don't need to provide the ability to specify an end date)
            // $table->timestamp('publish_start_date')->nullable();
            // $table->timestamp('publish_end_date')->nullable();
        });

        Schema::create('message_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'message');
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_revisions');
        Schema::dropIfExists('messages');
    }
};
