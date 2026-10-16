<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->unsignedInteger('position')->default(0)->after('page_key');
        });

        Schema::table('page_translations', function (Blueprint $table): void {
            $table->string('header_title', 200)->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('page_translations', function (Blueprint $table): void {
            $table->dropColumn('header_title');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn('position');
        });
    }
};
