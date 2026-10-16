<?php

namespace App\Http\Controllers\Twill;

use App\Models\ServiceCategory;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;

class ServiceController extends BaseModuleController
{
    protected $moduleName = 'services';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('slug')->label('URL slug'));
        $form->add(
            Select::make()
                ->name('category')
                ->label('Service category')
                ->options(ServiceCategory::query()->orderBy('position')->orderBy('title')->pluck('title', 'title')->all())
                ->default('For Organizations')
        );
        $form->add(Input::make()->name('excerpt')->label('Short introduction'));
        $form->add(Wysiwyg::make()->name('description')->label('Description'));
        $form->add(Input::make()->name('icon')->label('Icon character'));
        $form->add(Input::make()->name('badge')->label('Card badge'));
        $form->add(Input::make()->name('duration')->label('Typical duration'));
        $form->add(Input::make()->name('group_size')->label('Typical group size'));
        $form->add(Input::make()->name('position')->label('Display order')->note('Lower numbers appear first on the Services page and homepage.'));
        $form->add(Wysiwyg::make()->name('highlights')->label('Programme highlights (add as a bulleted list)'));
        $form->add(
            Medias::make()
                ->name('cover')
                ->label('Programme card and detail image')
                ->max(1)
        );
        $form->add(Checkbox::make()->name('featured')->label('Feature on the homepage'));

        return $form;
    }
}
