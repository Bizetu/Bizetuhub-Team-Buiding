<?php

namespace App\Models;

use A17\Twill\Models\Model;

class ContactInterest extends Model
{
    protected $fillable = [
        'title',
        'position',
        'published',
    ];

    protected $casts = [
        'position' => 'integer',
        'published' => 'boolean',
    ];
}
