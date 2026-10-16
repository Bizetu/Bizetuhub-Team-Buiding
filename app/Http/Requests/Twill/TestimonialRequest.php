<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class TestimonialRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required'],
            'title.*' => ['required', 'string', 'max:200'],
            'description' => ['required'],
            'description.*' => ['required', 'string', 'max:3000'],
            'client' => ['nullable', 'string', 'max:160'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'featured' => ['boolean'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
