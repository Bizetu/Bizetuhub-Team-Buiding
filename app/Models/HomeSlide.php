<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Model;

class HomeSlide extends Model
{
    use HasMedias;
    use HasFiles;
    use HasPosition;

    public array $filesParams = ['animation'];

    public array $mediasParams = [
        'background' => [
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
        'eyebrow',
        'description',
        'button_label',
        'button_url',
        'image_url',
        'video_url',
        'position',
        'published',
    ];

    protected $casts = [
        'position' => 'integer',
        'published' => 'boolean',
    ];
}
