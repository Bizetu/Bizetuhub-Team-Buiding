<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class ContactInterestRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:120', Rule::unique('contact_interests', 'title')],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'title' => ['required', 'string', 'max:120', Rule::unique('contact_interests', 'title')->ignore($this->route('contactInterest'))],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
