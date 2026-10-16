<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;

class EventController extends BaseModuleController
{
    protected $moduleName = 'events';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('slug')->label('Key'));
        $form->add(Wysiwyg::make()->name('description')->label('Description'));
        $form->add(Input::make()->name('venue')->label('Venue'));
        $form->add(DatePicker::make()->name('starts_at')->label('Starts at'));
        $form->add(DatePicker::make()->name('ends_at')->label('Ends at')->allowClear());
        $form->add(Input::make()->name('capacity')->label('Participant capacity')->type('number'));
        $form->add(Input::make()->name('fee')->label('Applicable fee')->type('number')->step('0.01')->note('Leave empty if the event is free.'));
        $form->add(
            Medias::make()
                ->name('card')
                ->label('Card image')
                ->max(1)
        );

        return $form;
    }
}
