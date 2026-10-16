<?php

namespace App\Models;

use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'title',
        'organisation',
        'name',
        'email',
        'phone',
        'service',
        'service_id',
        'event_id',
        'preferred_date',
        'participants',
        'venue_preference',
        'requirements',
        'status',
        'internal_notes',
        'published',
    ];

    protected $casts = ['preferred_date' => 'date'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function serviceProgramme(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
