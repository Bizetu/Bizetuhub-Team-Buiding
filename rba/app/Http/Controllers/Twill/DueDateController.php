<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use Illuminate\Contracts\Foundation\Application;
use A17\Twill\Services\Forms\Fields\MultiSelect;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Listings\TableColumn;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\DueDateRepository;
use Illuminate\Support\Facades\Auth;
use A17\Twill\Services\Forms\Form;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\DueDate;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class DueDateController extends BaseModuleController
{
    protected $moduleName = 'dueDates';

    protected $indexOptions = [
        'permalink' => false,
    ];

    protected $filters = ['company_id', 'job_role_id'];
    protected $titleColumnKey = 'title';

    protected $indexWith = ['company','jobRole'];

    public function __construct(Application $app, Request $request, DueDateRepository $repository)
    {
        parent::__construct($app, $request);
    }

    public function filters(): TableFilters
    {
        $isHR = auth()->guard('twill_users')->user()->is_hr;

        $tableFilters = TableFilters::make();

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('course_id')
                ->label('Course')
                ->options(collect(Course::published()->orderBy('display_name')->pluck('display_name', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('course_id', $v))
        );

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('job_role_id')
                ->label('Job Role')
                ->options(collect(JobRole::orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('job_role_id', $v))
        );

        if (! $isHR) {
            $tableFilters->add(
                MultiSelectFilter::make()
                    ->queryString('company_id')
                    ->label('Company')
                    ->options(collect(Company::orderBy('title')->pluck('title', 'id')->toArray()))
                    ->apply(fn (Builder $b, array $v) => $b->whereIn('company_id', $v))
            );
        }

        return $tableFilters;
    }

    public function additionalTableActions(): array
    {
        return [
            'resetFiltersAction' => [
                'name'    => 'Clear Filters',
                'variant' => 'ghost',
                'size'    => 'small',
                'link'    => route('twill.dueDates.index'),
                'target'  => '',
                'type'    => 'a',
            ],
        ];
    }

    public function getIndexTableColumns(): TableColumns
    {
        $tableColumns = TableColumns::make();
        if ($this->getIndexOption('publish')) {
            $tableColumns->add(
                PublishStatus::make()->title(twillTrans('twill::lang.listing.columns.published'))->sortable()->optional()
            );
        }

        $tableColumns->add(Text::make()->field($this->titleColumnKey)->linkToEdit()->sortable());
        $tableColumns->add(Text::make()->field('company_name')->title('Company')->sortable()->sortKey('company_id')->optional());
        $tableColumns->add(Text::make()->field('branch_name')->title('Branch')->sortable()->sortKey('branch_id')->optional());
        $tableColumns->add(Text::make()->field('department_name')->title('Department')->sortable()->sortKey('department_id')->optional());
        $tableColumns->add(Text::make()->field('job_role_name')->title('Job Role')->sortable()->sortKey('job_role_id')->optional());
        $tableColumns->add(Text::make()->field('date_due')->title('Due Date')->sortable()->sortKey('due_date')->optional());

        return $tableColumns;
    }

    protected function getDueDateRepository()
    {
        $model = 'DueDate';
        return App::make("App\Repositories\\" . $model . 'Repository');
    }

    protected function formData($request)
    {
        $r = explode('/', $request->getRequestUri());
        $course = DueDate::where('id', $r[3])->value('course_id');
        $activeJobRole = DueDate::where('id', $r[3])->pluck('job_role_id')[0];
        // Get selected values for editing
        $selectedCourses = [];
        $selectedJobRoles = [];

        if (isset($r[3]) && is_numeric($r[3])) {
            $existingRecord = DueDate::find($r[3]);
            if ($existingRecord) {
                $selectedCourses = [$existingRecord->course_id];
                if ($existingRecord->job_role_id) {
                    $selectedJobRoles = [$existingRecord->job_role_id];
                }
            }
        }

        if (!$course) {
            session(['parent_course' => $r[3]]);
        }

        $courseList = Course::published()->orderBy('display_name', 'ASC')->pluck('display_name', 'id');
        $companyList = Collection::make(Company::published()->orderBy('title')->pluck('title', 'id')->toArray());
        $jobRoleList = Collection::make(JobRole::published()->orderBy('title')->pluck('title', 'id')->toArray());

        return [
            'courseList' => $courseList,
            'r' => $r,
            'course' => $course,
            'companyList' => $companyList,
            'jobRoleList' => $jobRoleList,
            'selectedCourses' => $selectedCourses,
            'selectedJobRoles' => $selectedJobRoles,
            'activeJobRole' => $activeJobRole,
        ];
    }

    public function getFilterAll()
    {
        if (auth()->guard('twill_users')->user() && (auth()->guard('twill_users')->user()->role_id === 1 || auth()->guard('twill_users')->user()->role_id === 2 || auth()->guard('twill_users')->user()->role_id === 3)) {
            $filters = [
                'company_id' => 'company_id',
                'job_role_id' => 'job_role_id',
            ];
        }
        if (auth()->guard('twill_users')->user() && (auth()->guard('twill_users')->user()->role_id === 3)) {
            $filters = [
                'job_role_id' => 'job_role_id',
                'company_id' => 'company_id',
            ];
        }

        return $filters;
    }


    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        $parameter = Str::singular(str_after_last($this->moduleName, '.'));
        $id = $this->request->route()->parameter($parameter, $id);

        $input = $this->request->all();

        $company_id = isset($input['company_id']) ? $input['company_id'] : null;

        if(Auth::user()->role_id === 3 && Auth::user()->company_id) {
            $company_id = Auth::user()->company_id;
        }

        // Handle single course_id conversion to array
        if (!empty($input['course_id'])) {
            $input['course_id[]'] = [$input['course_id']];
        }

        // Handle single job_role_id conversion to array
        if (!empty($input['job_role_id'])) {
            $input['job_role_id[]'] = [$input['job_role_id']];
        }

        if (!empty($input['course_id[]']) && is_array($input['course_id[]'])) {

            // Get job roles array (could be empty for general due dates)
            $jobRoles = !empty($input['job_role_id[]']) ? $input['job_role_id[]'] : [null];

            foreach ($input['course_id[]'] as $course) {
                $courseModel = Course::where('id', $course)->first();
                $courseName = $courseModel ? $courseModel->display_name : null;

                // Create due dates for each job role (or general if no job roles selected)
                foreach ($jobRoles as $jobRoleId) {
                    // Check if due date already exists for this combination
                    $dueDate = DueDate::where('course_id', $course)
                        ->where('company_id', $company_id)
                        ->where('job_role_id', $jobRoleId)
                        ->first();

                    if (!$dueDate) {
                        // Create new due date
                        $dueDate = new DueDate();
                    }

                    // Set/update the due date properties
                    $dueDate->title = $courseName;
                    $dueDate->course_id = $course;
                    $dueDate->company_id = $company_id;
                    $dueDate->job_role_id = $jobRoleId; // This will be null for general due dates
                    $dueDate->due_date = $input['due_date'];

                    // Set published status based on save type
                    if (in_array($input['cmsSaveType'], ['save', 'update', 'publish'])) {
                        $dueDate->published = true;
                    } else {
                        $dueDate->published = true; // You had this as true in both cases
                    }

                    $dueDate->save();
                }
            }

            // Clean up: Delete the original record if it was a temporary one
            if (session('parent_course')) {
                DueDate::withTrashed()->where('id', session('parent_course'))->forceDelete();
                session()->forget('parent_course');
            }

            if (!empty($input['course_id'])) {
                return $this->respondWithRedirect(moduleRoute(
                    $this->moduleName,
                    $this->routePrefix,
                    'index'
                ));
            }

            return $this->respondWithRedirect(moduleRoute(
                $this->moduleName,
                $this->routePrefix,
                'index'
            ));
        }

        return $this->respondWithError('Something went wrong. Kindly try again');
    }
}
