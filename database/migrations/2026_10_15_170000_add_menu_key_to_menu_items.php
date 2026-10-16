<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table): void {
            $table->string('menu_key', 120)->nullable()->after('title')->index();
        });

        $applicationHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        DB::table('menu_items')->select(['id', 'url'])->orderBy('id')->chunkById(100, function ($items) use ($applicationHost): void {
            foreach ($items as $item) {
                $url = (string) $item->url;
                $host = parse_url($url, PHP_URL_HOST);

                if ($host !== null && $host !== $applicationHost) {
                    continue;
                }

                $path = parse_url($url, PHP_URL_PATH);
                if (! is_string($path) || $path === '') {
                    continue;
                }

                $key = $path === '/' ? 'home' : Str::slug(trim($path, '/'));
                if ($key !== '' && array_key_exists($key, Page::PAGE_KEYS)) {
                    DB::table('menu_items')->where('id', $item->id)->update(['menu_key' => $key]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropIndex(['menu_key']);
            $table->dropColumn('menu_key');
        });
    }
};
