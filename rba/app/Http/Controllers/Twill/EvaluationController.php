<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\NestedModuleController as BaseModuleController;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Columns;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Form;
use App\Models\Course;

class EvaluationController extends BaseModuleController
{
    protected $moduleName = 'evaluations';
    protected $showOnlyParentItemsInBrowsers = true;
    protected $nestedItemsDepth = 1;
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->enableReorder();
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $courseOptions = Course::published()->get();

        $courses = $courseOptions->map(function ($courseOption) {
            return Option::make($courseOption->id, $courseOption->display_name);
        })->toArray();

        $form->add(Select::make()->name('course_id')->label('Course')->placeholder('Select Course')->options(Options::make($courses)));
        $form->add(Input::make()->name('position')->label('Position'));

        $form->add(BlockEditor::make()->blocks(['evaluation'])->label('Add Evaluation'));

        return $form;
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $table = parent::getIndexTableColumns();
        $table->add(Text::make()->field('course_title_value')->title('Course'));

        return $table;
    }
}
