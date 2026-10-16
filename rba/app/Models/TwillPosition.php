<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwillPosition extends Model
{
    //

    protected $fillable = [
        'user_id',
        'position',
        'g_refresh_tok',
    ];

    protected $casts = [
        'position' => 'encrypted',

    ];
}
