<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class ServiceCategoryRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('service_categories', 'title')],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('service_categories', 'title')->ignore($this->route('serviceCategory'))],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
