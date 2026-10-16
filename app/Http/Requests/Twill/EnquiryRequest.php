<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class EnquiryRequest extends Request
{
    public function rulesForCreate(): array
    {
        return $this->enquiryRules();
    }

    public function rulesForUpdate(): array
    {
        return $this->enquiryRules();
    }

    private function enquiryRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string'],
            'status' => ['required', Rule::in(['new', 'under_review', 'completed'])],
            'internal_notes' => ['nullable', 'string'],
        ];
    }
}
