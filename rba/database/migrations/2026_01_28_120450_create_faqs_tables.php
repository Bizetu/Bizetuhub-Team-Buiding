<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('title', 200)->nullable();
            $table->text('answer')->nullable();
            $table->bigInteger('faq_category_id')->nullable();
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('faq_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'faq');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq_revisions');
        Schema::dropIfExists('faqs');
    }
};
