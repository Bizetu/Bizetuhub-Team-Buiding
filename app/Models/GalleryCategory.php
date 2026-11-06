<?php

namespace App\Models;

use A17\Twill\Models\Model;

class GalleryCategory extends Model
{
    protected static function booted(): void
    {
        static::creating(function (self $category): void {
            if ($category->position === null) {
                $category->position = ((int) static::query()->max('position')) + 1;
            }
        });
    }

    protected $fillable = [
        'title',
        'position',
        'published',
    ];

    protected $casts = [
        'position' => 'integer',
        'published' => 'boolean',
    ];
}
