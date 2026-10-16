<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Model;

class GalleryItem extends Model
{
    use HasMedias, HasPosition;

    public array $mediasParams = [
        'image' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 0,
                ],
            ],
        ],
    ];

    protected $fillable = [
        'title',
        'category',
        'alt_text',
        'caption',
        'published',
        'position',
    ];

    protected $casts = [
        'published' => 'boolean',
        'position' => 'integer',
    ];
}
