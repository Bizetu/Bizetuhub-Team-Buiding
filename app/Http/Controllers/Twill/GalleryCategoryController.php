<?php

namespace App\Http\Controllers\Twill;

use App\Models\GalleryCategory;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;

class GalleryCategoryController extends BaseModuleController
{
    protected $moduleName = 'galleryCategories';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $nextPosition = ((int) GalleryCategory::query()->max('position')) + 1;
        $form->add(
            Input::make()
                ->name('position')
                ->label('Display order')
                ->type('number')
                ->default($nextPosition)
        );

        return $form;
    }
}
