<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarAccessToken extends Model
{
    protected $fillable = [
        'user_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
    ];

    protected $casts = [
        'google_token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
