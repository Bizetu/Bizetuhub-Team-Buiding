<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_licenses', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('job_role_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->datetime('expired_at')->nullable();
            $table->string('position')->nullable();
        });
    }

    public function down()
    {
        
        Schema::dropIfExists('user_licenses');
    }
};
