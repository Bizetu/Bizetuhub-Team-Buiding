<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailUnsubscribe extends Model
{
    use HasFactory;

        protected $fillable = [
            'email',
            'group_id',
            'unsubscribed_at',
    ];
}
