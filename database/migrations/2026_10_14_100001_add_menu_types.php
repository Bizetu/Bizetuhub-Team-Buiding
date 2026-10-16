<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table): void {
            $table->string('menu_type')->default('main')->after('location')->index();
        });

        DB::table('menu_items')
            ->where('location', 'footer')
            ->update(['menu_type' => 'footer']);
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropIndex(['menu_type']);
            $table->dropColumn('menu_type');
        });
    }
};
