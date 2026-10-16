<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Model;

class Event extends Model
{
    use HasMedias;

    public array $mediasParams = [
        'card' => [
            'default' => [
                ['name' => 'default', 'ratio' => 4 / 3],
            ],
        ],
    ];

    protected $fillable = ['title', 'slug', 'description', 'venue', 'starts_at', 'ends_at', 'capacity', 'fee', 'published'];

    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'fee' => 'decimal:2', 'published' => 'boolean'];

    public function getCanPublishAttribute(): bool
    {
        return filled($this->description) && filled($this->venue) && $this->starts_at !== null;
    }
}
