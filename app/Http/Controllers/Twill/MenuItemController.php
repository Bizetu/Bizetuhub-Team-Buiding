<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Form;
use App\Models\MenuType;

class MenuItemController extends BaseModuleController
{
    protected $moduleName = 'menuItems';

    protected $defaultOrders = [
        'position' => 'asc',
    ];

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('menu_key')->label('Key')->note('Enter the page key, for example home.'));
        $form->add(
            Select::make()
                ->name('menu_type')
                ->label('Menu type')
                ->options(MenuType::where('published', true)->orderBy('position')->orderBy('title')->pluck('title', 'key')->all())
        );
        $form->add(Input::make()->name('position')->label('Twill position')->type('number')->min(0));
        $form->add(Checkbox::make()->name('open_new_tab')->label('Open in a new tab'));

        return $form;
    }

    protected function additionalIndexTableColumns(): \A17\Twill\Services\Listings\TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(
            \A17\Twill\Services\Listings\Columns\Text::make()->field('menu_key')->title('Menu key')
        );
        $table->add(
            \A17\Twill\Services\Listings\Columns\Text::make()->field('menu_type')->title('Menu type')
        );
        $table->add(
            \A17\Twill\Services\Listings\Columns\Text::make()->field('position')->title('Twill position')
        );

        return $table;
    }
}
