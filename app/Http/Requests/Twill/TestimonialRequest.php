<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class TestimonialRequest extends Request
{
    protected function prepareForValidation(): void
    {
        // Twill's quick-create modal only collects the title. Keep entries
        // without testimonial content as drafts until the full form is saved.
        if (! filled($this->input('description'))) {
            $this->merge([
                'published' => false,
                'publish' => false,
            ]);
        }
    }

    public function rulesForCreate(): array
    {
        $publishing = $this->boolean('publish') || $this->boolean('published');

        return [
            'title' => ['required'],
            'title.*' => ['required', 'string', 'max:200'],
            'description' => [Rule::requiredIf($publishing), 'nullable'],
            'description.*' => ['nullable', 'string', 'max:3000'],
            'client' => ['nullable', 'string', 'max:160'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'featured' => ['boolean'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
