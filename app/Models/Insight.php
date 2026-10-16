<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Model;

class Insight extends Model 
{
    use HasTranslation, HasSlug;

    protected $fillable = [
        'published',
        'title',
        'description',
        'category',
        'reading_time',
    ];
    
    public $translatedAttributes = [
        'title',
        'description',
    ];

    protected $casts = [
        'published' => 'boolean',
        'reading_time' => 'integer',
    ];

    public function getPlainDescriptionAttribute(): string
    {
        $text = preg_replace('/<\/(?:p|h[1-6]|li|blockquote|br)>/i', "\n\n", $this->description ?? '');

        return trim(strip_tags($text ?? ''));
    }
    
    public $slugAttributes = [
        'title',
    ];
    
}
