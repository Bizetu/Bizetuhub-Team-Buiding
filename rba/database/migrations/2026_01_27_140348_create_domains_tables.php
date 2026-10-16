<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('name', 200)->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('position')->unsigned()->nullable();
        });
    }

    public function down()
    {
        
        Schema::dropIfExists('domains');
    }
};
