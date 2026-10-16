<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WysiwygDiv extends Component
{
    public string $content;
    
    public function __construct(?string $content = '')
    {
        $this->content = $content ?? '';
    }

    public function render(): View|Closure|string
    {
        return view('components.wysiwyg-div');
    }
}
