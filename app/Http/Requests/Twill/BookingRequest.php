<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class BookingRequest extends Request
{
    public function rulesForCreate(): array
    {
        return $this->bookingRules();
    }

    public function rulesForUpdate(): array
    {
        return $this->bookingRules();
    }

    private function bookingRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'organisation' => ['required', 'string', 'max:160'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'service_id' => ['nullable', 'required_without:event_id', 'integer', 'exists:services,id'],
            'event_id' => ['nullable', 'required_without:service_id', 'integer', 'exists:events,id'],
            'preferred_date' => ['required', 'date'],
            'participants' => ['required', 'integer', 'min:2', 'max:10000'],
            'venue_preference' => ['nullable', 'string', 'max:200'],
            'requirements' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['new', 'under_review', 'confirmed', 'completed', 'cancelled'])],
            'internal_notes' => ['nullable', 'string'],
        ];
    }
}
