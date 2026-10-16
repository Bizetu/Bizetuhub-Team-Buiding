<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use App\Models\FaqCategory;

class FaqController extends BaseModuleController
{
    protected $moduleName = 'faqs';
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

        $faqCategories = FaqCategory::published()->get();

        $faqCategoriesOptions = $faqCategories->map(function ($category) {
            return Option::make($category->id, $category->title);
        })->toArray();

        $faqCategorySelect = Select::make('faq_category_id') 
            ->name('faq_category_id')
            ->label('Category')
            ->options(Options::make($faqCategoriesOptions))
            ->placeholder("Select Category");

        $form->add(
            Input::make()->name('title')->label('Question')
        );

        $wysiwygOptions = [
            ['header' => [2, 3, 4, 5, 6, false]],
            'bold',
            'italic',
            'underline',
            'strike',
            'blockquote',
            'code-block',
            'ordered',
            'bullet',
            'hr',
            'code',
            'link',
            'clean',
            'table',
            'align',
        ];

        $form->add(
            Wysiwyg::make()
                ->name('answer')
                ->toolbarOptions($wysiwygOptions)
                ->allowSource(true)
                ->maxLength(200)
                ->note('Answer')
        );

        $form->add($faqCategorySelect);

        $form->add(
            Input::make()->name('position')->label('Position')
        );

        $form->add(
            Medias::make()
                ->name('faq_image')
                ->label('Image')
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
            Text::make()->field('category_value')->title('Category')
        );

        $table->add(
            Text::make()->field('answer')->title('Answer')
        );

        return $table;
    }
}
