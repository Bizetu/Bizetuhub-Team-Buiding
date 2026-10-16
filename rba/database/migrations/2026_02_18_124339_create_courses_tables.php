<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            // this will create an id, a "published" column, and soft delete and timestamps columns
            createDefaultTableFields($table);
            
            $table->string('course_id')->unique();
            $table->string('display_name')->nullable();
            $table->string('short_name')->nullable();
            $table->text('short_description')->nullable();
            $table->text('overview')->nullable();
            $table->text('more_info')->nullable();
            $table->string('effort')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->dateTime('enrol_start')->nullable();
            $table->dateTime('enrol_end')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->float('price')->default(0);
            $table->string('course_image_uri')->nullable();
            $table->string('course_video_uri')->nullable();
            $table->bigInteger('course_category_id')->unsigned()->nullable();
            $table->boolean('status')->default(0);
            $table->string('slug')->nullable();
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->string('course_video')->nullable();
            $table->string('self_paced')->nullable();
            $table->string('mobile_available')->nullable();
            $table->string('certificates_display_behavior')->nullable();
            $table->string('_pre_requisite_courses_json')->nullable();
            $table->text('advanced_modules')->nullable();
            $table->text('pass_mark')->nullable();
            $table->text('enable_subsection_gating')->nullable();
            $table->string('has_reflections')->nullable();
            $table->integer('position')->unsigned()->nullable();
            $table->bigInteger('course_type_id')->nullable();
            $table->string('gradeable')->default('0');
            $table->string('parent_status', 255)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
        });

        Schema::create('course_slugs', function (Blueprint $table) {
            createDefaultSlugsTableFields($table, 'course');
        });

        Schema::create('course_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'course');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_revisions');
        Schema::dropIfExists('course_slugs');
        Schema::dropIfExists('courses');
    }
};
