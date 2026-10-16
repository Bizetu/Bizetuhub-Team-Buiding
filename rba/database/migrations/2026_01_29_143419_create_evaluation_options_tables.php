<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_options', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('title', 200)->nullable();
            $table->string('type')->nullable();
            $table->string('value1')->nullable();
            $table->string('value2')->nullable();
            $table->string('value3')->nullable();
            $table->string('value4')->nullable();
            $table->string('value5')->nullable();
            
            $table->integer('position')->unsigned()->nullable();

            // this will create the required columns to support nesting for this module
            $table->nestedSet();
        });

        Schema::create('evaluation_option_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'evaluation_option');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_option_revisions');
        Schema::dropIfExists('evaluation_options');
    }
};
