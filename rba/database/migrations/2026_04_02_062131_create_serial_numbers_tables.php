<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('serial_numbers', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            $table->string('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('course_id')->nullable();
            $table->integer('certificate_type')->default(2);
            $table->string('serial_number')->nullable();
            $table->integer('position')->unsigned()->nullable();

        });

        

        

        
    }

    public function down()
    {
        
        Schema::dropIfExists('serial_numbers');
    }
};
