<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('title', 200)->nullable();
            $table->string('contact_first_name')->nullable();
            $table->string('contact_last_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('company_code')->nullable();
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('company_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'company');
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_revisions');
        Schema::dropIfExists('companies');
    }
};
