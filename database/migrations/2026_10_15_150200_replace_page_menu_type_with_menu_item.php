<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pages', 'menu_item_id')) {
            Schema::table('pages', function (Blueprint $table): void {
                $table->foreignId('menu_item_id')
                    ->nullable()
                    ->after('page_key')
                    ->constrained('menu_items')
                    ->nullOnDelete();
            });
        }

        if (Schema::hasColumn('pages', 'menu_type_id')) {
            DB::table('pages')->whereNotNull('page_key')->orderBy('id')->get(['id', 'page_key'])->each(function ($page): void {
                $url = $page->page_key === 'home' ? '/' : '/'.$page->page_key;
                $menuItemId = DB::table('menu_items')->where('url', $url)->value('id');

                if ($menuItemId) {
                    DB::table('pages')->where('id', $page->id)->update(['menu_item_id' => $menuItemId]);
                }
            });

            Schema::table('pages', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('menu_type_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pages', 'menu_item_id')) {
            Schema::table('pages', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('menu_item_id');
            });
        }

        if (! Schema::hasColumn('pages', 'menu_type_id')) {
            Schema::table('pages', function (Blueprint $table): void {
                $table->foreignId('menu_type_id')
                    ->nullable()
                    ->constrained('menu_types')
                    ->nullOnDelete();
            });
        }
    }
};
