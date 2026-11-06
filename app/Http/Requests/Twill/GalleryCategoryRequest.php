<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use Illuminate\Validation\Rule;

class GalleryCategoryRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('gallery_categories', 'title')],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('gallery_categories', 'title')->ignore($this->route('galleryCategory'))],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
