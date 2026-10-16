<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Files;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class CaseStudyController extends BaseModuleController
{
    protected $moduleName = 'caseStudies';
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disablePermalink();
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('description')
            ->type('textarea')
            ->label('Description')
        );

        $form->add(
            Input::make()->name('publication_year')
            ->label('Year of Publication')
        );

        $form->add(
            Medias::make()
                ->name('cover_image')
                ->label(twillTrans('Cover image'))
        );

        $form->add(
            Files::make()
            ->name('ppt')
            ->label(twillTrans('Power Point Presentation'))
            ->note('Add one file (per language)')
        );

        $form->add(
            Files::make()
            ->name('pdf')
            ->label(twillTrans('PDF File'))
            ->note('Add one file (per language)')
        );

        $form->add(
            Files::make()
                ->name('video')
                ->label(twillTrans('CaseStudy Video'))
        );

        return $form;
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('description')->title('Description')
        );
        return $table;
    }
}
