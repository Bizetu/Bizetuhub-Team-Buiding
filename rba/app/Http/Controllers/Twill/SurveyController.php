<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Columns;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Forms\Option;
use Illuminate\Support\Facades\Auth;
use A17\Twill\Services\Forms\Form;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Branch;

class SurveyController extends BaseModuleController
{
    protected $moduleName = 'surveys';
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disablePermalink();
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $companiesOptions = Company::published()->get()->map(function ($company) {
            return Option::make($company->id, $company->title);
        })->toArray();

        $companySelect = Select::make()->name('company_id')->label('Company')->options(Options::make($companiesOptions))->placeholder("Select Company")->searchable();

        $jobRole = JobRole::published()->get()->map(function ($jobRole) {
            return Option::make($jobRole->id, $jobRole->title);
        })->toArray();

        $jobRoleSelect = Select::make()->name('job_role_id')->label('Job Role')->options(Options::make($jobRole))->placeholder("Select Job Role")->searchable();

        $department = Department::published()->get()->map(function ($department) {
            return Option::make($department->id, $department->title);
        })->toArray();

        $departmentSelect = Select::make()->name('department_id')->label('Department')->options(Options::make($department))->placeholder("Select Department")->searchable();

        $branch = Branch::published()->get()->map(function ($branch) {
            return Option::make($branch->id, $branch->title);
        })->toArray();

        $branchSelect = Select::make()->name('branch_id')->label('Branch')->options(Options::make($branch))->placeholder("Select Branch")->searchable();

        $user = auth()->guard('twill_users')->user() ?? Auth::user();

        if ($user && ($user->role_id == 3 || $user->role_value == 'Company HR' || $user->is_company_admin == 1) && $user->company_id) {
            if (!$model->id || !$model->company_id) {
                $model->company_id = $user->company_id;
            }

            $companySelect->disabled();
            $jobRoleSelect->disabled();
            $departmentSelect->disabled();
            $branchSelect->disabled();
        }

        $form->addFieldset(
            Fieldset::make()->title('Survey Details')->id('survey_details')->fields([
                Columns::make()->left([$companySelect])->right([$branchSelect]),
                Columns::make()->left([$jobRoleSelect])->right([$departmentSelect]),
                Input::make()->name('description')->label('Description'),
                BlockEditor::make()->blocks(['survey'])->label('Add Survey'),
            ])
        );

        return $form;
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(Text::make()->field('description')->title('Description'));
        $table->add(Text::make()->field('company_title')->title('Company'));
        $table->add(Text::make()->field('job_role_title')->title('Job Role'));
        $table->add(Text::make()->field('department_title')->title('Department'));
        $table->add(Text::make()->field('branch_title')->title('Branch'));

        return $table;
    }
}
