<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Model;

class Service extends Model
{
    use HasMedias;

    public array $mediasParams = [
        'cover' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 4 / 3,
                ],
            ],
        ],
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'description',
        'icon',
        'badge',
        'duration',
        'group_size',
        'highlights',
        'position',
        'featured',
        'published',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published' => 'boolean',
    ];

    public function getCanPublishAttribute(): bool
    {
        return filled($this->excerpt);
    }

    public function getHighlightsListAttribute(): array
    {
        $highlights = $this->highlights ?? '';

        preg_match_all('/<li\b[^>]*>(.*?)<\/li>/is', $highlights, $items);
        $values = $items[1] ?: preg_split('/[\r\n]+/', strip_tags($highlights));

        return collect($values)
            ->map(fn (string $value): string => trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')))
            ->filter()
            ->values()
            ->all();
    }
}
