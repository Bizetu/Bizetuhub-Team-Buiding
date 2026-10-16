<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuItemRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));

        if (! filled($this->input('menu_key')) && $title !== '') {
            $key = Str::slug($title);
            $this->merge(['menu_key' => $key === 'homepage' ? 'home' : $key]);
        }

        $this->merge([
            'menu_type' => $this->input('menu_type') ?: 'main',
            'location' => $this->input('location') ?: ($this->input('menu_type') === 'footer' ? 'footer' : 'header'),
            'open_new_tab' => $this->boolean('open_new_tab'),
        ]);

        if ($this->input('position') === null) {
            $this->merge(['position' => ((int) MenuItem::max('position')) + 1]);
        }
    }

    public function rulesForCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'menu_key' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'menu_type' => ['required', 'string', Rule::exists('menu_types', 'key')->where('published', true)],
            'position' => ['nullable', 'integer', 'min:0'],
            'open_new_tab' => ['boolean'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
