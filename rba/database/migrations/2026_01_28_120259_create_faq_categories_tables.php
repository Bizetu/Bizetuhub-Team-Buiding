<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('faq_categories', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('faq_category_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'faq_category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq_category_revisions');
        Schema::dropIfExists('faq_categories');
    }
};
