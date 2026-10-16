<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class CompanyCourse extends Model implements Sortable
{
    use HasBlocks, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'course_id',
        'position',
    ];

    public function getCourseIdAttribute($value)
    {
        return collect(json_decode($value))->map(function($item) {
            return ['id' => $item];
        })->all();
    }
    
    public function setCourseIdAttribute($value)
    {
        $this->attributes['course_id'] = collect($value)->filter()->values();
    }
        
    protected $casts = [
        'course_id' => 'array'  
    ];
    
}
