<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('service_categories')) {
            Schema::create('service_categories', function (Blueprint $table): void {
                $table->id();
                $table->string('title', 100)->unique();
                $table->unsignedInteger('position')->default(0);
                $table->boolean('published')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        foreach (['For Organizations', 'For Professionals'] as $position => $title) {
            DB::table('service_categories')->updateOrInsert(
                ['title' => $title],
                ['position' => $position, 'published' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
