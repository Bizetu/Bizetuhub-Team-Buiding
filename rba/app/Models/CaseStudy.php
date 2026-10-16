<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class CaseStudy extends Model implements Sortable
{
    use HasBlocks, HasMedias, HasFiles, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'publication_year',
        'description',
        'position',
    ];

    protected $with = ['medias','files'];

    public $mediasParams = [
        'cover_image' => [
            'default' => [
                [
                    'name' => 'landscape',
                ]
            ]
        ],
        'hero_image' => [
            'default' => [
                [
                    'name' => 'landscape',
                ]
            ]
        ],
        'mobile_hero_image' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
            'mobile' => [
                [
                    'name' => 'mobile',
                    'ratio' => 1,
                ],
            ]
        ]
    ];

    
    
    public $filesParams = ['pdf','video','ppt'];
}
