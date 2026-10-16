<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use A17\Twill\Models\Behaviors\Sortable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model implements Sortable
{
    use HasBlocks, HasMedias, HasSlug, HasTranslation, HasPosition;

    public array $mediasParams = [
        'hero' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 3 / 1,
                ],
            ],
        ],
        'page_background' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 2 / 1,
                ],
            ],
        ],
    ];

    public const PAGE_KEYS = [
        'home' => 'Homepage',
        'services' => 'Services',
        'events' => 'Events',
        'industries' => 'Industries',
        'gallery' => 'Gallery',
        'packages' => 'Packages',
        'testimonials' => 'Testimonials',
        'faqs' => 'FAQs',
        'privacy' => 'Privacy policy',
        'terms' => 'Terms of use',
        'insights' => 'Insights',
        'about' => 'About',
        'contact' => 'Contact',
        'book' => 'Booking',
    ];

    protected $fillable = [
        'published',
        'page_key',
        'menu_item_id',
        'position',
        'page_background_color',
        'title',
        'header_title',
        'description',
    ];

    protected $casts = [
        'page_key' => 'string',
        'position' => 'integer',
        'published' => 'boolean',
    ];

    public $translatedAttributes = [
        'title',
        'header_title',
        'description',
    ];

    public $slugAttributes = [
        'title',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
