<?php

namespace App\Http\Controllers\Twill;

use App\Models\GalleryCategory;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;

class GalleryItemController extends BaseModuleController
{
    protected $moduleName = 'galleryItems';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(
            Select::make()
                ->name('category')
                ->label('Category')
                ->options(GalleryCategory::query()->orderBy('position')->orderBy('title')->pluck('title', 'title')->all())
        );
        $form->add(Input::make()->name('alt_text')->label('Accessible image description'));
        $form->add(Wysiwyg::make()->name('caption')->label('Caption'));
        $form->add(
            Medias::make()
                ->name('image')
                ->label('Gallery image')
                ->max(1)
        );

        return $form;
    }
}
