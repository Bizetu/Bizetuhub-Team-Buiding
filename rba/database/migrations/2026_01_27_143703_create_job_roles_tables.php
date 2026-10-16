<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_roles', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('title', 200)->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('job_role_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'job_role');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_role_revisions');
        Schema::dropIfExists('job_roles');
    }
};
