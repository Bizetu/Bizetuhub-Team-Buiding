<?php

namespace App\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    protected $fillable = ['title', 'url', 'menu_key', 'location', 'menu_type', 'position', 'open_new_tab', 'published'];

    public function getUrlAttribute(mixed $value): mixed
    {
        $path = parse_url((string) $value, PHP_URL_PATH);

        return parse_url((string) $value, PHP_URL_HOST) === null && rtrim((string) $path, '/') === '/home'
            ? '/'
            : $value;
    }

    public function getHrefAttribute(): string
    {
        $key = $this->menu_key;

        if ($key && Route::has($key)) {
            return route($key);
        }

        if ($key && array_key_exists($key, Page::PAGE_KEYS)) {
            return $key === 'home' ? route('home') : '/'.$key;
        }

        if ($key) {
            return route('pages.root', ['slug' => $key]);
        }

        return $this->url ?: '#';
    }

    protected $casts = [
        'position' => 'integer',
        'open_new_tab' => 'boolean',
        'published' => 'boolean',
    ];
}
