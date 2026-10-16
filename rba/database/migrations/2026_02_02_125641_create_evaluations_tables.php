<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('title', 200)->nullable();
            $table->bigInteger('course_id')->unsigned()->nullable();
            $table->bigInteger('evaluation_option_id')->unsigned()->nullable();
            $table->integer('position')->unsigned()->nullable();

            // this will create the required columns to support nesting for this module
            $table->nestedSet();
        });

        Schema::create('evaluation_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'evaluation');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_revisions');
        Schema::dropIfExists('evaluations');
    }
};
