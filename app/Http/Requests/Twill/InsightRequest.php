<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class InsightRequest extends Request
{
    protected function prepareForValidation(): void
    {
        // Twill's quick-create modal only collects the title. Keep articles
        // without body content as drafts until the full form is completed.
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
            'description.*' => ['nullable', 'string', 'max:50000'],
            'category' => ['nullable', 'string', 'max:100'],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:120'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
