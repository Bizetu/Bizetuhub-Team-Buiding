<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Forms\Option;
use Illuminate\Support\Facades\Auth;
use A17\Twill\Services\Forms\Form;
use App\Models\Company;

class BranchController extends BaseModuleController
{
    protected $moduleName = 'branches';
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disablePermalink();
    }

    public function getCreateForm(): Form
    {
        $user = auth()->guard('twill_users')->user() ?? Auth::user();

        $companiesOptions = Company::published()->get()->map(function ($company) {
            return Option::make($company->id, $company->title);
        })->toArray();

        $companySelect = Select::make()->name('company_id')->label('Company')->options(Options::make($companiesOptions))->placeholder('Select Company')->searchable();

        $isHr = $user && $user->is_hr;

        if ($isHr) {
            $companySelect->default($user->company_id);
            $companySelect->disabled();
        }

        return Form::make([
            Input::make()->name('title')->label('Title')->required(),
            $companySelect,
        ]);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $companiesOptions = Company::published()->get()->map(function ($company) {
            return Option::make($company->id, $company->title);
        })->toArray();

        $companySelect = Select::make()->name('company_id')->label('Company')->options(Options::make($companiesOptions))->placeholder("Select Company")->searchable();
        $user = auth()->guard('twill_users')->user() ?? Auth::user();

        if ($user && $user->is_hr) {
            if (!$model->id || !$model->company_id) {
                $model->company_id = $user->company_id;
            }

            $companySelect->disabled();
        }

        $form->add($companySelect);

        return $form;
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(Text::make()->field('company_value')->title('Company'));

        return $table;
    }
}
