<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);    
                    
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('department_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'department');
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_revisions');
        Schema::dropIfExists('departments');
    }
};
