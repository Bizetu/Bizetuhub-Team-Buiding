<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;

use App\Models\MenuType;
use App\Repositories\MenuTypeRepository;

class MenuController extends BaseModuleController
{
    protected $moduleName = 'menus';

    protected $defaultOrders = [
        'position' => 'asc',
    ];

    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->enableReorder();
    }

    public function getCreateForm(): Form
    {
        $menuTypes = MenuType::published()->get();

        $menuTypeOptions = $menuTypes->map(function ($menuType) {
            return Option::make($menuType->id, $menuType->title);
        })->toArray();

        return Form::make([
            Input::make()->name('title')->label('Menu Title')->note('Menu display name, e.g. "Key"')->required(),
            Input::make()->name('key')->label('Key')->note('Slug-like url'),
            Select::make()->name('menu_type_id')->label('Menu Type')->placeholder('Select Menu Type')->options(Options::make($menuTypeOptions))
        ]);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(Input::make()->name('position')->label('Position'));
        $form->add(Input::make()->name('key')->label('Key'));

        $menuTypes = MenuType::published()->get();

        $menuTypeOptions = $menuTypes->map(function ($menuType) {
            return Option::make($menuType->id, $menuType->title);
        })->toArray();

        $form->add(Select::make()->name('menu_type_id')->label('Menu Type')->options(Options::make($menuTypeOptions)));

        return $form;
    }

    protected function formData($request)
    {
        $menuTypes = app(MenuTypeRepository::class)->listAll('title');

        return [
            'menuTypeList' => $menuTypes,
        ];
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(Text::make()->field('menu_type_value')->title('Menu Type'));
        $table->add(Text::make()->field('key')->title('Key'));
        $table->add(Text::make()->field('position')->title('Position'));

        return $table;
    }
}
