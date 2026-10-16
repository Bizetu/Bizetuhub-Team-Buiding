<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            $table->string('client')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('featured')->default(false);

            // add those 2 columns to enable publication timeframe fields (you can use publish_start_date only if you don't need to provide the ability to specify an end date)
            // $table->timestamp('publish_start_date')->nullable();
            // $table->timestamp('publish_end_date')->nullable();
        });

        Schema::create('testimonial_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'testimonial');
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('testimonial_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'testimonial');
        });

        
    }

    public function down()
    {
        
        Schema::dropIfExists('testimonial_translations');
        Schema::dropIfExists('testimonial_slugs');
        Schema::dropIfExists('testimonials');
    }
};
