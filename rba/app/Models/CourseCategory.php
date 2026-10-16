<?php

namespace App\Models;


use A17\Twill\Models\Model;

class CourseCategory extends Model 
{
    protected $fillable = [
        'published',
        'title',
        'description',
    ];
}
