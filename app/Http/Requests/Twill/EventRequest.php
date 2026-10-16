<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EventRequest extends Request
{
    protected function prepareForValidation(): void
    {
        // Twill's quick-create modal only provides a title and permalink.
        // Keep incomplete events as drafts until the details are completed.
        $hasEventDetails = filled($this->input('description'))
            && filled($this->input('venue'))
            && filled($this->input('starts_at'));

        if (! $hasEventDetails) {
            $this->merge([
                'published' => false,
                'publish' => false,
            ]);
        }

        // Twill's create modal may submit the permalink as a localized value
        // (for example: ['en' => 'hiking-event']). Events store one plain slug.
        $slug = $this->input('slug');
        if (is_array($slug)) {
            $slug = $slug[config('translatable.fallback_locale')] ?? reset($slug);
        }

        $slugSource = $slug ?: ($this->input('permalink') ?: $this->input('title'));

        $this->merge([
            'slug' => Str::slug(last(explode('/', trim((string) $slugSource, '/')))),
        ]);
    }

    public function rulesForCreate(): array
    {
        $publishing = $this->boolean('publish') || $this->boolean('published');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($this->route('event'))],
            'description' => [Rule::requiredIf($publishing), 'nullable', 'string'],
            'venue' => [Rule::requiredIf($publishing), 'nullable', 'string', 'max:255'],
            'starts_at' => [Rule::requiredIf($publishing), 'nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'fee' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
