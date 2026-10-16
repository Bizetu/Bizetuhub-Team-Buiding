<?php

namespace App\Models;

use A17\Twill\Models\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'title',
        'name',
        'email',
        'organisation',
        'phone',
        'interest',
        'message',
        'status',
        'internal_notes',
        'published',
    ];
}
