<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Behaviors\HasFiles;
use Illuminate\Support\Facades\Storage;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Model;

class Course extends Model implements Sortable
{
    use HasSlug, HasMedias, HasFiles, HasRevisions, HasPosition;
    
    public $slugAttributes = [
        'short_name',
    ];

    protected $fillable = [
        'course_id',
        'display_name',
        'short_name',
        'short_description',
        'overview',
        'more_info',
        'effort',
        'start',
        'end',
        'enrol_start',
        'enrol_end',
        'price',
        'course_image_uri',
        'course_video_uri',
        'course_category_id',
        'status',
        'slug',
        'order_id',
        'course_video',
        'due_date',
        'has_reflections',
        'published',
        'course_type_id',
        'gradeable',
        'position',
        'self_paced',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'start' => 'datetime',
        'end' => 'datetime',
        'enrol_start' => 'datetime',
        'enrol_end' => 'datetime',
        'due_date' => 'datetime',
    ];

    protected $guarded = [];

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

    public function getImageWithFallbackAttribute(): string
    {
        if ($this->hasImage('cover_image')) {
            return $this->image('cover_image', 'default');
        }  elseif (!empty($this->course_image_uri)) {
            return $this->course_image_uri;
        } else {
            return asset('images/defaults/coffee_banner.jpg');
        }
    }

    public function getCourseEffortAttribute()
    {
        if ($this->effort && strpos($this->effort, ':') !== false) {
            list($days, $hours, $minutes) = array_map('intval', explode(':', $this->effort));
            return (($days * 24 * 60) + ($hours * 60) + $minutes);
        }
        return 0;
    }

    public function getIsMandatoryAttribute()
    {
        return 1;
    }
}
