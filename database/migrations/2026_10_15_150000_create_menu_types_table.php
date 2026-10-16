<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_types', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 100)->unique();
            $table->string('key', 100)->unique();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('published')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        foreach ([['Main menu', 'main'], ['Footer menu', 'footer']] as $position => [$title, $key]) {
            DB::table('menu_types')->insert([
                'title' => $title,
                'key' => $key,
                'position' => $position,
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_types');
    }
};
