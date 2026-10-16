<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class MenuTypeController extends BaseModuleController
{
    protected $moduleName = 'menuTypes';

    protected $defaultOrders = [
        'position' => 'asc',
    ];

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('key')->label('Menu type key')->note('Use a unique key, for example main or footer.'));
        $form->add(Input::make()->name('position')->label('Twill position')->type('number')->min(0));

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('key')->title('Key'));
        $table->add(Text::make()->field('position')->title('Twill position'));

        return $table;
    }
}
