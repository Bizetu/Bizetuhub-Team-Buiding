<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Forms\Fields\MultiSelect;
use A17\Twill\Services\Forms\Fields\Select;
use App\Models\Company;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use Illuminate\Support\Facades\Auth;

class CompanyCourseController extends BaseModuleController
{
    protected $moduleName = 'companyCourses';
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
        $companies = Company::published()->get();
        $courses = Company::published()->get();

        $companyOptions = $companies->map(function ($company) {
            return Option::make($company->id, $company->title);
        })->toArray();

        $courseOptions = $courses->map(function ($course) {
            return Option::make($course->id, $course->title);
        })->toArray();

        // Get the authenticated user - will be null on first call
        $user = auth()->guard('twill_users')->user() ?? Auth::user();

        $companySelect = Select::make('company_id')
            ->name('company_id')
            ->label('Select Company')
            ->options(
                Options::make($companyOptions)
            );

        // Only apply COMPANYHR logic if user is authenticated
        if ($user && $user->role_value === "Company HR" && $user->company_id) {
            $companySelect->disabled();

            // If it's a new model, set the company_id
            if (!$model->id) {
                $model->company_id = $user->company_id;
            }
        }

        $form->add($companySelect);

        $form->add(
            MultiSelect::make('course_id')
             ->name('course_id')
            ->label('Select Courses')
            ->options(
                Options::make($courseOptions)
            )
        );

        return $form;
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        return $table;
    }
}
