<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use App\Models\MenuType;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuTypeRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));
        if ($title !== '' && ! filled($this->input('key'))) {
            $this->merge(['key' => Str::slug($title)]);
        }

        if ($this->input('position') === null) {
            $this->merge(['position' => ((int) MenuType::max('position')) + 1]);
        }
    }

    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('menu_types', 'title')],
            'key' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('menu_types', 'key')],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'title' => ['required', 'string', 'max:100', Rule::unique('menu_types', 'title')->ignore($this->route('menuType'))],
            'key' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('menu_types', 'key')->ignore($this->route('menuType'))],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
