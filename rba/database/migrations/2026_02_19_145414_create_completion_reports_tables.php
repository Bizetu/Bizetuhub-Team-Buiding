<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('completion_reports', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->bigInteger('user_id')->unsigned();
            $table->string('username');
            $table->string('email');
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->bigInteger('job_role_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->string('course')->nullable();
            $table->string('employee_number')->nullable();
            $table->string('country_id')->nullable();
            $table->decimal('score', 6, 4)->nullable();
            $table->decimal('max_score')->nullable();
            $table->string('completion_status')->nullable();
            $table->timestamp('enrollment_date', 6)->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->bigInteger('user_license_id')->nullable();
            $table->string('effort_hours')->nullable();
            
            $table->index(['job_role_id','department_id','branch_id']);
            $table->index('user_license_id');
            $table->index('company_id');
            $table->index('course_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        
        Schema::dropIfExists('completion_reports');
    }
};
