<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\UserLicensesExport;
use Illuminate\Http\JsonResponse;
use App\Models\UserLicense;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Course;
use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Repeater;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class UserLicenseController extends BaseModuleController
{
    protected $moduleName = 'userLicenses';
    protected $titleColumnLabel = 'Username';
    protected $titleColumnKey = 'username';
    protected $titleFormLabel = 'Username';
    protected $defaultIndexOptions = ['export' => true];
    protected $defaultOrders = ['created_at' => 'desc'];
    protected $perPage = 1000;
    protected $filters = [
        'company_id'      => 'company_id',
        'department_id'   => 'department_id',
        'job_role_id'     => 'job_role_id',
        'branch_id'       => 'branch_id',
        'course_id'       => 'course_id',
        'created_at'  => 'created_at',

    ];
    protected $indexWith = ['jobRole', 'company', 'department', 'branch', 'course'];
    protected $labels = [
        'published' => 'Published',
        'draft'     => 'Archived',
        'listing'   => ['filter' => ['published' => 'Published', 'draft' => 'Archived']],
    ];
    protected ?array $searchColumns = ['email', 'username'];

    protected function setUpController(): void
    {
        $this->indexOptions['create']   = false;
        $this->indexOptions['edit']     = false;
        $this->indexOptions['publish']  = false;
        $this->indexOptions['bulkEdit'] = false;
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();

        $columns->add(Text::make()->field('username')->title('Name'));
        $columns->add(Text::make()->field('email')->title('Email'));

        if (auth()->guard('twill_users')->user()->role_value !== 'Company HR') {
            $columns->add(Text::make()->field('company_title_value')->title('Company'));
        }

        $columns->add(Text::make()->field('job_role_title_value')->title('Job Role'));
        $columns->add(Text::make()->field('department_title_value')->title('Department'));
        $columns->add(Text::make()->field('branch_title_value')->title('Branch'));
        $columns->add(Text::make()->field('course_title_value')->title('Course'));
        $columns->add(Text::make()->field('date_created')->title('Enrollment Date'));

        return $columns;
    }

    // public function filters(): TableFilters
    // {
    //     $isHR = auth()->guard('twill_users')->user()->is_hr;

    //     $tableFilters = TableFilters::make();

    //     $tableFilters->add(
    //         MultiSelectFilter::make()
    //             ->queryString('course_id')
    //             ->label('Course')
    //             ->options(collect(Course::published()->orderBy('display_name')->pluck('display_name', 'id')->toArray()))
    //             ->apply(fn (Builder $b, array $v) => $b->whereIn('course_id', $v))
    //     );

    //     $tableFilters->add(
    //         MultiSelectFilter::make()
    //             ->queryString('branch_id')
    //             ->label('Branch')
    //             ->options(collect(Branch::orderBy('title')->pluck('title', 'id')->toArray()))
    //             ->apply(fn (Builder $b, array $v) => $b->whereIn('branch_id', $v))
    //     );

    //     $tableFilters->add(
    //         MultiSelectFilter::make()
    //             ->queryString('department_id')
    //             ->label('Department')
    //             ->options(collect(Department::orderBy('title')->pluck('title', 'id')->toArray()))
    //             ->apply(fn (Builder $b, array $v) => $b->whereIn('department_id', $v))
    //     );

    //     $tableFilters->add(
    //         MultiSelectFilter::make()
    //             ->queryString('job_role_id')
    //             ->label('Job Role')
    //             ->options(collect(JobRole::orderBy('title')->pluck('title', 'id')->toArray()))
    //             ->apply(fn (Builder $b, array $v) => $b->whereIn('job_role_id', $v))
    //     );

    //     if (! $isHR) {
    //         $tableFilters->add(
    //             MultiSelectFilter::make()
    //                 ->queryString('company_id')
    //                 ->label('Company')
    //                 ->options(collect(Company::orderBy('title')->pluck('title', 'id')->toArray()))
    //                 ->apply(fn (Builder $b, array $v) => $b->whereIn('company_id', $v))
    //         );
    //     }

    //     return $tableFilters;
    // }

    protected function indexData($request)
    {
        $CompanyhrRoles = ['COMPANYHR'];
        $completionStatuses = collect(['Completed' =>'Completed', 'Failed' => 'Failed', 'In Progress'=>'In Progress']);
        $coursesList = Course::published()->orderBy('short_name')->get(['id', 'display_name as title']);
        $departmentList = Department::published()->orderBy('title')->get(['id', 'title']);
        $user = auth()->guard('twill_users')->user();

        if ($user && in_array($user->roleValue, $CompanyhrRoles)) {

            $companyList = Company::published()->orderBy('title')->where('id', $user->company_id)->get(['id', 'title']);
            $branchList = Branch::where('company_id', $user->company_id)->orderBy('title')->get(['id', 'title']);
            $jobRoleList = JobRole::published()->where('company_id', $user->company_id)->orderBy('title')->get(['id', 'title']);
        } else {
            $companyList = Company::published()->orderBy('title')->get(['id', 'title']);
            $branchList = Branch::published()->orderBy('title')->get(['id', 'title']);
            $jobRoleList = JobRole::published()->orderBy('title')->get(['id', 'title']);
        }

        return [
            'company_idList' =>  $companyList,
            'branch_idList' => $branchList,
            'department_idList' =>  $departmentList,
            'job_role_idList' =>  $jobRoleList,
            'course_idList' => $coursesList,
            'created_atList' => $completionStatuses,
        ];
    }

/**
     * @return IlluminateView|JsonResponse
     */
    public function index(?int $parentModuleId = null): mixed
    {
        $this->authorizeOption('list', $this->moduleName);

        $parentModuleId = $this->getParentModuleIdFromRequest($this->request) ?? $parentModuleId;

        $this->submodule = isset($parentModuleId);
        $this->submoduleParentId = $parentModuleId;

        $indexData = $this->getIndexData(
            $this->submodule ? [
                $this->getParentModuleForeignKey() => $this->submoduleParentId,
            ] : []
        );

        if ($this->request->ajax() || $this->request->expectsJson()) {
            return new JsonResponse($indexData + ['replaceUrl' => true]);
        }

        if ($this->request->has('openCreate') && $this->request->get('openCreate')) {
            $indexData += ['openCreate' => true];
        }

        $form = $this->getCreateForm();

        if (
            $form->filter(function (BaseFormField $field) {
                return $field instanceof BlockEditor ||
                    $field instanceof Repeater;
            })
                ->isNotEmpty()
        ) {
            throw new \Exception('Create forms do not support repeaters and blocks');
        }

        $route = request()->route()->getName();
        $currentRoute = route($route);

        $view = $route;

        return View::make($view, $indexData + ['repository' => $this->repository])
            ->with(['formBuilder' => $form->toFrontend(isCreate: true)]);
    }

    public function additionalTableActions(): array
    {
        return [
            'resetFilters' => [
                'name'    => 'Clear Filters',
                'variant' => 'ghost',
                'size'    => 'small',
                'link'    => route('twill.userLicenses.index'),
                'target'  => '',
                'type'    => 'a',
            ],
            'exportAction' => [
                'name'    => 'Export',
                'variant' => 'primary',
                'size'    => 'small',
                'link'    => route('twill.export.userLicenses'),
                'target'  => '',
                'type'    => 'a',
            ],
        ];
    }

    public function export(): BinaryFileResponse
    {
        $query = UserLicense::query()->orderBy('created_at', 'desc');

        $user = auth()->guard('twill_users')->user();
        $isHR = $user->is_hr;
        if ($isHR) {
            $query->where('company_id', $user->company_id);
        }

        // Parse filters from the referer URL.
        // Keys are queryStrings (e.g. "course_id"), NOT labels ("Course").
        // Values may be a plain string (single) or array (multi) — (array) handles both.
        $referer = $this->request->header('referer');
        if ($referer) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $queryParams);

            if (!empty($queryParams['filter'])) {
                // The filter value in the URL is singly encoded by URLSearchParams.
                // parse_str already decoded it once, so JSON.parse is sufficient.
                $filter = json_decode($queryParams['filter'], true) ?? [];

                if (!empty($filter['status']) && $filter['status'] !== 'all') {
                    $query->where('status', $filter['status']);
                }

                if (!empty($filter['search'])) {
                    $query->where(function ($q) use ($filter) {
                        $q->where('username', 'like', '%' . $filter['search'] . '%')
                          ->orWhere('email', 'like', '%' . $filter['search'] . '%');
                    });
                }

                $multiSelectColumns = ['course_id','branch_id','department_id','job_role_id','company_id'];

                foreach ($multiSelectColumns as $col) {
                    if (!empty($filter[$col]) && $filter[$col] !== 'all') {
                        $query->whereIn($col, (array) $filter[$col]);
                    }
                }
            }
        }

        $fileName = 'Course_Enrollments_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';

        return Excel::download(new UserLicensesExport($query), $fileName);
    }

    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        return $this->respondWithSuccess('Ooops! You can preview edits, but not edit them.');
    }
}
