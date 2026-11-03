<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class GalleryItemRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            // The quick-create modal only asks for a title. Editors can add
            // the accessible image description in the full edit form.
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
