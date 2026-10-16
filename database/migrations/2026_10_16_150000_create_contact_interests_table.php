<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_interests', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 120)->unique();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('published')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        $now = now();
        DB::table('contact_interests')->insert([
            ['title' => 'Team Building Programs', 'position' => 0, 'published' => true, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Staff Training & Facilitation', 'position' => 1, 'published' => true, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'HR Support Systems', 'position' => 2, 'published' => true, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'CV Review & Revamp', 'position' => 3, 'published' => true, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'LinkedIn Optimization', 'position' => 4, 'published' => true, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Something else', 'position' => 5, 'published' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_interests');
    }
};
