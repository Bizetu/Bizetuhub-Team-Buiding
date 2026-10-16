<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Repeater;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Columns;
use A17\Twill\Services\Forms\Form;

class CompanyController extends BaseModuleController
{
    protected $moduleName = 'companies';

    protected $defaultOrders = [
        'title' => 'asc',
    ];

    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disablePermalink();
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(Text::make()->field('branch_count')->title('Branches')->optional());
        $table->add(Text::make()->field('domain_count')->title('Domains')->optional());
        $table->add(Text::make()->field('contact_first_name')->title('Contact First Name')->optional());
        $table->add(Text::make()->field('contact_last_name')->title('Contact Last Name')->optional());
        $table->add(Text::make()->field('contact_email')->title('Contact Email')->optional());
        $table->add(Text::make()->field('company_code')->title('Company Code')->optional());
        $table->add(Text::make()->field('position')->title('Position')->optional());
        $table->add(Text::make()->field('staff_number')->title('Staff Number'));
        $table->add(Text::make()->field('authentication_method')->title('Authentication Method'));

        return $table;
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $authMethods = [
            'Microsoft' => 'Microsoft',
            'Google' => 'Google',
            'Form' => 'Form'
        ];

        $form->addFieldset(
            Fieldset::make()->title('Company Details')->id('company_details')->fields([
                Columns::make()->left([Input::make()->name('contact_first_name')->label('Contact First Name')])->right([Input::make()->name('contact_last_name')->label('Contact Last Name')]),
                Columns::make()->left([Input::make()->name('contact_email')->label('Contact Email')])->right([Input::make()->name('company_code')->label('Company Code')]),
                Columns::make()->left([Input::make()->name('position')->label('Position')])->right([Input::make()->name('staff_number')->label('Staff Number')]),
                Select::make()->name('authentication_method')->label('Authentication Method')->placeholder('Select Authentication Method')->options($authMethods),
                Repeater::make()->type('domain')->label('Allowed Domains')
            ])
        );

        return $form;
    }
}
