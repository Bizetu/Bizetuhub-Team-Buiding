<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;
use App\Models\MenuItem;
use App\Models\Page;
use App\Rules\SafePageLink;
use Illuminate\Validation\Rule;

class PageRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $menuItemId = $this->input('menu_item_id');
        if (! $menuItemId) {
            return;
        }

        $menuKey = MenuItem::where('published', true)->whereKey($menuItemId)->value('menu_key');
        if (! $menuKey) {
            return;
        }

        $this->merge([
            'page_key' => array_key_exists($menuKey, Page::PAGE_KEYS) ? $menuKey : null,
        ]);
    }

    public function rulesForCreate(): array
    {
        return [
            'title' => ['required'],
            // Editors can publish one language while other language tabs remain empty.
            // The main title is still required above; translations are optional per locale.
            'title.*' => ['nullable', 'string', 'max:200'],
            'page_key' => ['nullable', 'string', Rule::in(array_keys(Page::PAGE_KEYS)), Rule::unique('pages', 'page_key')],
            'menu_item_id' => ['nullable', 'integer', Rule::exists('menu_items', 'id')->where('published', true)],
            'position' => ['nullable', 'integer', 'min:0'],
            'header_title' => ['nullable'],
            'header_title.*' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable'],
            'description.*' => ['nullable', 'string', 'max:20000'],
            'blocks.*.content.button_url' => ['nullable', 'string', 'max:2048', new SafePageLink],
            'blocks.*.content.link_url' => ['nullable', 'string', 'max:2048', new SafePageLink],
            'blocks.*.blocks.*.content.link_url' => ['nullable', 'string', 'max:2048', new SafePageLink],
        ];
    }

    public function rulesForUpdate(): array
    {
        $rules = $this->rulesForCreate();
        $rules['page_key'] = [
            'nullable',
            'string',
            Rule::in(array_keys(Page::PAGE_KEYS)),
            Rule::unique('pages', 'page_key')->ignore($this->route('page')),
        ];
        $rules['menu_item_id'] = ['nullable', 'integer', Rule::exists('menu_items', 'id')->where('published', true)];

        return $rules;
    }
}
