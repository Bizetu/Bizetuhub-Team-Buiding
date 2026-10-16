<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\NestedModuleController as BaseModuleController;

use A17\Twill\Services\Forms\Fields\Select;

class EvaluationOptionController extends BaseModuleController
{
    protected $moduleName = 'evaluationOptions';
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

        $form->add(
            Select::make()
                ->name('type')
                ->label('Type')
                ->placeholder('Select Type')
                ->options([
                    [
                        'value' => 'text',
                        'label' => 'Text'
                    ],
                    [
                        'value' => 'bar',
                        'label' => 'Bar'
                    ],
                    [
                        'value' => 'radio',
                        'label' => 'Radio'
                    ],
                ])
        );

        $form->add(
            Input::make()
                ->name('value1')
                ->label('Value 1')
                ->connectedTo('type', 'radio')
        );

        $form->add(
            Input::make()
                ->name('value2')
                ->label('Value 2')
                ->connectedTo('type', 'radio')
        );

        $form->add(
            Input::make()
                ->name('value3')
                ->label('Value 3')
                ->connectedTo('type', 'radio')
        );

        $form->add(
            Input::make()
                ->name('value4')
                ->label('Value 4')
                ->connectedTo('type', 'radio')
        );

        $form->add(
            Input::make()
                ->name('value5')
                ->label('Value 5')
                ->connectedTo('type', 'radio')
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
            Text::make()->field('type')->title('Type')
        );

        return $table;
    }
}
