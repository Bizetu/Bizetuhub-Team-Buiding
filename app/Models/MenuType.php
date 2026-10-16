<?php

namespace App\Models;

use A17\Twill\Models\Model;

class MenuType extends Model
{
    protected $fillable = ['title', 'key', 'position', 'published'];

    protected $casts = [
        'position' => 'integer',
        'published' => 'boolean',
    ];
}
