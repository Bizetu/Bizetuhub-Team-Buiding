<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class InsightRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [
            'title' => ['required'],
            'title.*' => ['required', 'string', 'max:200'],
            'description' => ['required'],
            'description.*' => ['required', 'string', 'max:50000'],
            'category' => ['required', 'string', 'max:100'],
            'reading_time' => ['required', 'integer', 'min:1', 'max:120'],
        ];
    }

    public function rulesForUpdate(): array
    {
        return $this->rulesForCreate();
    }
}
