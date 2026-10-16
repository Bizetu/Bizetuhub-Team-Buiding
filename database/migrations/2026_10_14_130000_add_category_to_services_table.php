<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('services', 'category')) {
            Schema::table('services', function (Blueprint $table): void {
                $table->string('category', 100)->nullable()->after('slug');
            });
        }

        DB::table('services')->whereNull('category')->update(['category' => 'For Organizations']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'category')) {
            Schema::table('services', function (Blueprint $table): void {
                $table->dropColumn('category');
            });
        }
    }
};
