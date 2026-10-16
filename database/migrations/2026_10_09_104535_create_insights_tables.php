<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('insights', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            $table->string('category')->default('Insights');
            $table->unsignedSmallInteger('reading_time')->default(5);

            // add those 2 columns to enable publication timeframe fields (you can use publish_start_date only if you don't need to provide the ability to specify an end date)
            // $table->timestamp('publish_start_date')->nullable();
            // $table->timestamp('publish_end_date')->nullable();
        });

        Schema::create('insight_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'insight');
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('insight_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'insight');
        });

        
    }

    public function down()
    {
        
        Schema::dropIfExists('insight_translations');
        Schema::dropIfExists('insight_slugs');
        Schema::dropIfExists('insights');
    }
};
