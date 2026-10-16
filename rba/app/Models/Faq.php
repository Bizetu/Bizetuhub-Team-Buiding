<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Faq extends Model implements Sortable
{
    use HasMedias, HasRevisions, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'answer',
        'faq_category_id',
        'position',
    ];

    protected $with = ['medias'];

    public $mediasParams = [
        'faq_image' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
            'mobile' => [
                [
                    'name' => 'landscape',
                    'ratio' => 16 / 9,
                ],
                [
                    'name' => 'portrait',
                    'ratio' => 3 / 4,
                ],
            ],
        ],
    ];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    public function getCategoryValueAttribute()
    {
        return $this->category ? $this->category->title : null;
    }
    
}
