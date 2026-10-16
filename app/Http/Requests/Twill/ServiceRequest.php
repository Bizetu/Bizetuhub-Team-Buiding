<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class ServiceRequest extends Request
{
    public function rulesForCreate(): array
    {
        $publishing = $this->boolean('publish') || $this->boolean('published');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($this->route('service'))],
            'category' => ['nullable', 'string', 'max:100', Rule::exists('service_categories', 'title')],
            'excerpt' => [Rule::requiredIf($publishing), 'nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:30'],
            'badge' => ['nullable', 'string', 'max:80'],
            'duration' => ['nullable', 'string', 'max:120'],
            'group_size' => ['nullable', 'string', 'max:120'],
            'highlights' => ['nullable', 'string', 'max:5000'],
            'position' => ['nullable', 'integer', 'min:0'],
            'featured' => ['boolean'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
