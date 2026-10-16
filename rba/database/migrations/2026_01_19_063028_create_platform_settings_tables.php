<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            // feel free to modify the name of this column, but title is supported by default (you would need to specify the name of the column Twill should consider as your "title" column in your module controller if you change it)
            $table->string('title', 200)->nullable();
            $table->text('tag_line')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('third_color')->nullable();
            $table->string('enroll_color')->nullable();
            $table->string('progress_color')->nullable();
            $table->string('retake_color')->nullable();
            $table->string('completed_color')->nullable();
            $table->string('menu_color')->nullable();
            $table->string('menu_color_active')->nullable();
            $table->string('footer_color')->nullable();
            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('platform_setting_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'platform_setting');
        });

        Schema::create('platform_setting_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'platform_setting');
        });
    }

    public function down()
    {
        Schema::dropIfExists('platform_setting_revisions');
        Schema::dropIfExists('platform_setting_slugs');
        Schema::dropIfExists('platform_settings');
    }
};
