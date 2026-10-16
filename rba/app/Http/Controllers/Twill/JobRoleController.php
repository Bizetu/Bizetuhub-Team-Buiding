<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;


use App\Repositories\CompanyRepository;
use App\Repositories\DepartmentRepository;
use App\Models\JobRole;
use App\Models\Company;
use App\Models\Department;
use App\Models\Course;
use A17\Twill\Services\Forms\Fields\MultiSelect;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class JobRoleController extends BaseModuleController
{
    protected $moduleName = 'jobRoles';

    protected $indexWith = ['company', 'department'];

    protected $defaultOrders = [
        'title' => 'asc',
    ];

    protected $perPage = 200;

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

        $departmentOptions = Department::published()->get()->map(function ($department) {
            return Option::make($department->id, $department->title);
        })->toArray();

        $departmentSelect = Select::make()->name('department_id')->label('Department')->options(Options::make($departmentOptions))->placeholder('Select Department')->searchable();

        $isHr = $user && $user->is_hr;

        if ($isHr) {
            $companySelect->default($user->company_id);
            $companySelect->disabled();
        }

        return Form::make([
            Input::make()->name('title')->label('Title')->required(),
            $companySelect,
            $departmentSelect,
        ]);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $jobRole = request()->route('jobRole');
        $companies = Company::published()->get();
        $departments = Department::published()->get();
        $courses = Course::published()->get();

        $companiesOptions = $companies->map(function ($domains) {
            return Option::make($domains->id, $domains->title);
        })->toArray();

        $departmentOptions = $departments->map(function ($department) {
            return Option::make($department->id, $department->title);
        })->toArray();

        $courseOptions = $courses->map(function ($course) {
            return Option::make($course->id, $course->short_name);
        })->toArray();

        // Get the authenticated user - will be null on first call
        $user = auth()->guard('twill_users')->user() ?? Auth::user();

        // Build company select field
        $companySelect = Select::make('company_id')
            ->name('company_id')
            ->label('Company')
            ->options(Options::make($companiesOptions))
            ->placeholder("Select Company");

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
            Select::make('department_id')
            ->name('department_id')
            ->label('Department')
            ->options(Options::make($departmentOptions))
            ->placeholder("Select Department")
        );

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

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(Text::make()->field('department_value')->title('Department')->sortKey('department_id'));
        $table->add(Text::make()->field('company_value')->title('Company')->sortKey('company_id'));
        $table->add(Text::make()->field('created_at')->title('Created On'));
        $table->add(Text::make()->field('updated_at')->title('Updated On'));

        return $table;
    }

    protected function formData($request)
    {
        if(Auth::user()->is_hr){
            session(['company'=>Auth::user()->company_id]);
        }

        return [
            'companyList' => app(CompanyRepository::class)->published()->pluck('title', 'id'),
            'departmentList' => app(DepartmentRepository::class)->published()->pluck('title', 'id'),
            'authUser' => Auth::user(),
        ];
    }
}
