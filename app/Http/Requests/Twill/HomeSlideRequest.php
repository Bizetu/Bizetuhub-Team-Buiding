<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use App\Rules\SafePageLink;

class HomeSlideRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'button_label' => ['nullable', 'required_with:button_url', 'string', 'max:80'],
            'button_url' => [
                'nullable',
                'required_with:button_label',
                'string',
                'max:2048',
                new SafePageLink,
            ],
            'image_url' => ['nullable', 'string', 'max:2048', new SafePageLink],
            'video_url' => ['nullable', 'string', 'max:2048', new SafePageLink],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
