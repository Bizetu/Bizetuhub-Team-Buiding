<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 100)->unique();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('published')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('gallery_categories')->insert([
            'title' => 'Team Building and Teamwork',
            'position' => 0,
            'published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('gallery_items')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->each(function (string $title, int $position): void {
                DB::table('gallery_categories')->updateOrInsert(
                    ['title' => $title],
                    [
                        'position' => $position + 1,
                        'published' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_categories');
    }
};
