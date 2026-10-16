<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Model;

class Testimonial extends Model 
{
    use HasTranslation, HasSlug;

    protected $fillable = [
        'published',
        'title',
        'description',
        'client',
        'rating',
        'featured',
    ];
    
    public $translatedAttributes = [
        'title',
        'description',
    ];

    protected $casts = [
        'published' => 'boolean',
        'featured' => 'boolean',
        'rating' => 'integer',
    ];
    
    public $slugAttributes = [
        'title',
    ];
    
}
