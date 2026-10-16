<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Files;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Form;

class HomeSlideController extends BaseModuleController
{
    protected $moduleName = 'homeSlides';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('eyebrow')->label('Small heading'));
        $form->add(Input::make()->name('description')->label('Slide message'));
        $form->add(Input::make()->name('button_label')->label('Button label'));
        $form->add(Input::make()->name('button_url')->label('Button link'));
        $form->add(
            Medias::make()
                ->name('background')
                ->label('Banner image')
                ->max(1)
        );
        $form->add(
            Input::make()
                ->name('image_url')
                ->label('Banner image URL')
                ->type('url')
                ->note('Optional direct link to a public image. The uploaded banner image takes priority.')
        );
        $form->add(
            Files::make()
                ->name('animation')
                ->label('Short animation video upload')
                ->note('MP4, WebM or MOV video, up to 50 MB. Video will autoplay muted and loop.')
                ->filesizeMax(50)
        );
        $form->add(
            Input::make()
                ->name('video_url')
                ->label('Animation video URL')
                ->type('url')
                ->note('Optional direct link to a public MP4, WebM or MOV video. The uploaded video takes priority.')
        );

        return $form;
    }
}
