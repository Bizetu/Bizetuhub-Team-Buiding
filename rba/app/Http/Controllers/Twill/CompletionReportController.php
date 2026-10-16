<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Exports\SubsidiaryCompletionsExport;
use A17\Twill\Services\Forms\Fields\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\JobRoleCompletionsExport;
use App\Exports\CompletionReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\CourseAverageExport;
use A17\Twill\Services\Forms\Form;
use Illuminate\Http\JsonResponse;
use App\Models\CompletionReport;
use Illuminate\Http\Request;
use App\Models\UserLicense;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use App\Jobs\ExportCompletionReports;
use A17\Twill\Helpers\FlashLevel;
use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Repeater;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class CompletionReportController extends BaseModuleController
{
    protected $moduleName       = 'completionReports';
    protected $titleColumnLabel = 'Email';
    protected $titleColumnKey   = 'email';
    protected $titleFormLabel   = 'email';
    protected $defaultIndexOptions = ['export' => true];
    protected $defaultOrders    = ['created_at' => 'desc'];
    protected $indexWith        = ['jobRole', 'company', 'department', 'branch', 'courseRelationship'];
    protected $perPage          = 1000;

    protected $filters = [
        'company_id'      => 'company_id',
        'department_id'   => 'department_id',
        'job_role_id'     => 'job_role_id',
        'branch_id'       => 'branch_id',
        'course_id'       => 'course_id',
        'completion_status'  => 'completion_status',
        'enrollment_date'  => 'enrollment_date',
        'completion_date'  => 'completion_date',

    ];

    protected function setUpController(): void
    {
        $this->indexOptions['create']   = false;
        $this->indexOptions['edit']     = false;
        $this->indexOptions['publish']  = false;
        $this->indexOptions['bulkEdit'] = false;

        $this->setSearchColumns(['username', 'email']);
    }

    // public function filters(): TableFilters
    // {
    //     $isHR = auth()->guard('twill_users')->user()->is_hr;

    //     $tableFilters = TableFilters::make();

    //     $completionStatuses = ['completed' =>'Completed', 'failed' => 'Failed', 'In Progress'=>'In Progress'];

    //     $tableFilters->add(
    //         MultiSelectFilter::make()
    //             ->queryString('completion_status')
    //             ->label('Status')
    //             ->options(collect($completionStatuses))
    //             ->apply(fn (Builder $b, array $v) => $b->whereIn('completion_status', $v))
    //     );

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

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();

        $columns->add(Text::make()->field('username')->title('Name'));
        $columns->add(Text::make()->field('email')->title('Email'));
        $columns->add(Text::make()->field('job_role_title_value')->title('Job Role'));

        if (Auth::user()->role_value !== 'Company HR') {
            $columns->add(Text::make()->field('company_title_value')->title('Company'));
        }

        $columns->add(Text::make()->field('department_title_value')->title('Department'));
        $columns->add(Text::make()->field('branch_title_value')->title('Branch'));
        $columns->add(Text::make()->field('course_title_value')->title('Course'));
        $columns->add(Text::make()->field('completion_status')->title('Completion Status'));
        $columns->add(Text::make()->field('date_enrolled')->title('Enrollment Date'));
        $columns->add(Text::make()->field('date_completed')->title('Completion Date'));

        return $columns;
    }

    public function additionalTableActions(): array
    {
        return [
            'exportAction' => [
                'name'    => 'Export',
                'variant' => 'primary',
                'size'    => 'small',
                'link'    => route('twill.export.completionReports'),
                'target'  => '',
                'type'    => 'a',
            ],
        ];
    }

    /* public function export(Request $request)
    {
        $user = Auth::user();

        // Parse filters from referer or request
        $filters = [];
        $referer = $request->header('referer');

        if ($referer) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $queryParams);
            if (!empty($queryParams['filter'])) {
                try {
                    $filters = json_decode($queryParams['filter'], true, 512, JSON_THROW_ON_ERROR);
                } catch (Throwable) {
                    $filters = [];
                }
            }
        }

        ExportCompletionReports::dispatch($filters, $user->id);

        return redirect()->back()->with('status', 'Your export is being processed. You will receive an email when it\'s ready.');
    } */

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
            'completion_statusList' => $completionStatuses,
            'enrollment_dateList' => $completionStatuses,
            'completion_dateList' => $completionStatuses,
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

        $view = 'twill.completionReports.index';

        return View::make($view, $indexData + ['repository' => $this->repository])
            ->with(['formBuilder' => $form->toFrontend(isCreate: true)]);
    }

    public function export(): BinaryFileResponse
    {
        $query   = CompletionReport::query()->orderBy('created_at', 'desc');
        $user = auth()->guard('twill_users')->user();
        $isHR = $user->is_hr;
        if ($isHR) {
            $query->where('company_id', $user->company_id);
        }
        $referer = $this->request->header('referer');
        $multiSelectColumns = [
            'course_id',
            'branch_id',
            'department_id',
            'job_role_id',
            'company_id'
        ];

        if ($referer) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $queryParams);

            if (!empty($queryParams['filter'])) {
                try {
                    $filter = json_decode($queryParams['filter'], true, 512, JSON_THROW_ON_ERROR);
                } catch (\Throwable) {
                    $filter = [];
                }

                if (!empty($filter['status']) && $filter['status'] !== 'all') {
                    $query->where('status', $filter['status']);
                }

                if (!empty($filter['search'])) {
                    $query->where(function ($q) use ($filter) {
                        $q->where('username', 'like', '%' . $filter['search'] . '%')
                          ->orWhere('email', 'like', '%' . $filter['search'] . '%');
                    });
                }

                foreach ($multiSelectColumns as $col) {
                    if (!empty($filter[$col]) && $filter[$col] !== 'all') {
                        $query->whereIn($col, (array) $filter[$col]);
                    }
                }

                if (!empty($filter['completion_status']) && $filter['completion_status'] !== 'all') {
                    $query->whereIn('completion_status', (array) $filter['completion_status']);
                }
            }
        }

        $fileName = 'Course_Completions_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';

        return Excel::download(new CompletionReportsExport($query),$fileName);
    }

    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        return $this->respondWithSuccess('Ooops! You can preview edits, but not edit them.');
    }

    // =========================================================================
    // Dashboard
    // =========================================================================

    public function dashboard(Request $request)
    {
        $launchYear = "2025";
        $eyear = date('Y');
        $cyear = date('Y');
        $years = range(now()->year, now()->year - 1);

        $lastLoginOption = $this->getLastLogin();
        $completionChartOptions = [
            'chart_title' => "Percentage of users who've completed all mandatory modules",
            'chart_type'  => 'pie',
            'series_data' => $this->getCompleteComplusoryModules(),
        ];
        $yearlyCompletionGraph = [
            'chart_title'    => 'Top 5 courses completed to date',
            'completion_data' => $this->getYearlyTopCompletions($cyear, $launchYear),
        ];
        $userCompletions = $this->getTopUserCompletions();
        $genderChartOptions = [
            'chart_title' => 'Onboarded User by Gender',
            'subtitle'    => 'Onboarded Users with completed profiles',
            'chart_type'  => 'pie',
            'series_data' => $this->getCompleteProfileGender(),
        ];

        return view('twill.completionReports.dashboard', compact(
            'completionChartOptions',
            'lastLoginOption',
            'yearlyCompletionGraph',
            'userCompletions',
            'genderChartOptions',
            'years'
        ));
    }

    // ─── Dashboard AJAX endpoints ─────────────────────────────────────────────

    public function eresults(Request $request)
    {
        $nmonth = date('m', strtotime($request->emonth));
        return response()->json($this->getTopMonthEnrollments($nmonth));
    }

    public function cresults(Request $request)
    {
        $nmonth = date('m', strtotime($request->cmonth));
        return response()->json($this->getTopMonthCompletions($nmonth));
    }

    public function yearlyEResults(Request $request)
    {
        return response()->json($this->getTopYearlyEnrollments($request->eyear));
    }

    public function yearlyCRresults(Request $request)
    {
        $year = $request->get('cyear', now()->year);

        return response()->json(
            $this->getYearlyTopCompletions($year, '2025')
        );
    }

    // ─── Dashboard data methods ───────────────────────────────────────────────

    public function getLastLogin()
    {
        $months = [
            'Jan' => 1, 'Feb' => 2, 'Mar' => 3,  'Apr' => 4,
            'May' => 5, 'Jun' => 6, 'Jul' => 7,  'Aug' => 8,
            'Sep' => 9, 'Oct' => 10,'Nov' => 11, 'Dec' => 12,
        ];

        $isHR = Auth::user()->role_id === 3 || Auth::user()->role_value === 'Company HR';

        return collect(
            array_map(function ($month) use ($isHR) {
                $q = User::published()->whereMonth('last_login_at', $month)->whereYear('last_login_at', date('Y'));
                if ($isHR) $q->where('company_id', Auth::user()->company_id);
                return $q->count();
            }, $months)
        );
    }

    public function getCompleteComplusoryModules()
    {
        $q = User::published();
        if (Auth::user()->role_value === 'Company HR') {
            $q->where('company_id', Auth::user()->company_id);
        }
        $totalCount = $q->count();
        $certified  = self::getCertified();
        $pct        = $totalCount > 0 ? (count($certified) / $totalCount) * 100 : 0;

        return collect([
            'incomplete' => ['name' => 'Incomplete Modules', 'value' => $pct,         'total' => $totalCount - count($certified)],
            'complete'   => ['name' => 'Completed Modules',  'value' => 100 - $pct,   'total' => count($certified)],
        ]);
    }

    public static function getCertified()
    {
        $companyId = Auth::user()->role_value === 'Company HR' ? Auth::user()->company_id : null;
        return User::getCertifiedUserIds($companyId);
    }

    public function getYearlyTopCompletions($year, $launchYear)
    {
        $q = CompletionReport::with('courseRelationship')
            ->whereYear('created_at', '>=', $launchYear)
            ->where('completion_status', 'Completed')
            ->selectRaw('course_id, count(*) as count')
            ->groupBy('course_id')
            ->orderByDesc('count')
            ->take(5);

        if ($year && $year != 0) {
            $q->whereYear('created_at', '<=', $year);
        }

        if (Auth::user()->role_value === 'Company HR') {
            $q->where('company_id', Auth::user()->company_id);
        }

        return $q->get()->map(function ($c) {
            return [
                'name' => optional($c->courseRelationship)->display_name ?? '-',
                'total' => $c->count,
            ];
        });
    }

    public function getTopUserCompletions()
    {
        $q = CompletionReport::where('user_id', '!=', 1)
            ->selectRaw('MIN(id) as id, user_id, MAX(score) as score, username, COUNT(*) as count')
            ->where('completion_status', 'Completed')
            ->groupBy('user_id', 'username')
            ->orderBy('count', 'desc')
            ->take(10);

        if (Auth::user()->role_value === 'Company HR') {
            $q->where('company_id', Auth::user()->company_id);
        }

        return $q->get()->map(fn ($c) => ['name' => $c->username, 'total' => $c->count]);
    }

    public function getCompleteProfileGender()
    {
        $base = User::published()->whereNotNull('gender_id');
        if (Auth::user()->role_value === 'Company HR') {
            $base->where('company_id', Auth::user()->company_id);
        }
        $total  = (clone $base)->count();
        $female = (clone $base)->where('gender_id', 1)->count();
        $male   = (clone $base)->where('gender_id', 2)->count();
        $undef  = (clone $base)->where('gender_id', 3)->count();

        $pct = fn ($n) => $total > 0 ? ($n / $total) * 100 : 0;

        return collect([
            'female'    => ['name' => 'Female Users',    'value' => $pct($female), 'total' => $female],
            'male'      => ['name' => 'Male Users',      'value' => $pct($male),   'total' => $male],
            'undefined' => ['name' => 'Undefined Users', 'value' => $pct($undef),  'total' => $undef],
        ]);
    }

    public function getTopMonthEnrollments($month)
    {
        return Course::published()->get()
            ->map(fn ($course) => [
                'name'  => $course->display_name,
                'total' => UserLicense::where('course_id', $course->id)->whereMonth('created_at', $month)->count(),
            ])
            ->filter(fn ($item) => $item['total'] > 0)
            ->values()
            ->toArray();
    }

    public function getTopMonthCompletions($month)
    {
        return Course::published()->get()
            ->map(fn ($course) => [
                'name'  => $course->display_name,
                'total' => CompletionReport::where('course_id', $course->id)->where('completion_status', 'Completed')->whereMonth('created_at', $month)->count(),
            ])
            ->filter(fn ($item) => $item['total'] > 0)
            ->values()
            ->toArray();
    }

    public function getTopYearlyEnrollments($year)
    {
        $launchYear = '2025';
        $eyear      = date('Y');

        $q = $year == 0
            ? UserLicense::whereYear('created_at', '>=', $launchYear)->whereYear('created_at', '<=', $eyear)
            : UserLicense::whereYear('created_at', '=', $year);

        return $q->selectRaw('id, course_id, count(*) as count')
            ->groupBy('course_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->map(fn ($row) => [
                'name'  => optional($row->course)->display_name,
                'total' => $row->count,
            ])
            ->filter(fn ($item) => $item['name'])
            ->values()
            ->toArray();
    }

    public function getTopYearlyCompletions($year)
    {
        $launchYear = '2025';
        $eyear      = date('Y');

        $q = $year == 0
            ? CompletionReport::whereYear('created_at', '>=', $launchYear)->whereYear('created_at', '<=', $eyear)
            : CompletionReport::whereYear('created_at', '=', $year);

        return $q->where('completion_status', 'Completed')
            ->selectRaw('id, course_id, score, count(*) as count')
            ->groupBy('course_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->map(fn ($row) => ['name' => $row->course, 'total' => $row->count])
            ->filter(fn ($item) => $item['name'])
            ->values()
            ->toArray();
    }

    public function subsidiaryCompletion(Request $request)
    {
        $companies = Company::published()->with('users');
        $activeSearchTerm = $request->input('search');

        if ($activeSearchTerm) {
            $companies->where('title', 'LIKE', '%' . $activeSearchTerm . '%');
        }

        $companies = $companies->paginate(50);

        return view('twill.completionReports.subsidiaryCompletion', compact(
            'companies',
            'activeSearchTerm',
        ));
    }

    public function exportSubsidiaryCompletions(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Company::query()->published();

        if ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }

        $companies = $query->get();

        $title = 'Company_Completion_Reports_RBA_as_at_' . now()->addHours(3)->format('d-m-Y_H-i-s') . '.xlsx';

        return Excel::download(new SubsidiaryCompletionsExport($companies), $title);
    }

    public function courseAverage(Request $request)
    {
        if ($request->isMethod('post')) {
            if (empty($request->course_id) && empty($request->search)) {
                return redirect()->back()->withErrors("Please select at least one filter");
            }
        }
        $currentCompany = (Company::where('id', Auth::user()?->company_id)->value('title') . ' - Average Score per Module Reports') ?? 'RBA - Average Score per Module Reports';
        $isSuperAdmin = Auth::user()->role_value === 'SUPERADMIN';
        $query = CompletionReport::selectRaw("
                course_id,
                COUNT(DISTINCT user_id) as total_completed,
                AVG(
                    CASE
                        WHEN max_score > 0
                        THEN (score / max_score) * 100
                        ELSE NULL
                    END
                ) as average_score
            ");

        if (!$isSuperAdmin) {
            $query->where('company_id', Auth::user()->company_id);
        }

        if (!empty($request->course_id)) {
            $query->whereIn('course_id', $request->course_id);
        }

        if (!empty($request->search)) {
            $query->whereHas('courseRelationship', function ($q) use ($request) {
                $q->where('display_name', 'LIKE', "%{$request->search}%");
            });
        }

        $completions = $query->groupBy('course_id')->get();

        $allCourses = Course::published()->orderBy('display_name', 'ASC')->get();

        if ($completions->isEmpty()) {
            return view('twill.completionReports.courseAverage', [
                'reportData' => [],
                'allCourses' => $allCourses,
                'activeCourseId' => $request->course_id,
                'company_value' => $currentCompany,
            ]);
        }

        $courseIds = $completions->pluck('course_id');
        $courses = Course::whereIn('id', $courseIds)->get()->keyBy('id');

        $reportData = $completions->map(function ($completion) use ($courses) {
            return [
                'course_id' => $completion->course_id,
                'title' => optional($courses->get($completion->course_id))->display_name ?? '-',
                'total_completed' => $completion->total_completed,
                'average_score' => number_format($completion->average_score ?? 0, 2),
            ];
        });

        return view('twill.completionReports.courseAverage', [
            'reportData' => $reportData,
            'allCourses' => $allCourses,
            'activeCourseId' => $request->course_id,
            'company_value' => $currentCompany,
        ]);
    }

    public function exportCourseAverage(Request $request)
    {
        $courseIds = $request->input('course_id');
        $search = $request->input('search');
        $isSuperAdmin = Auth::user()->role_value === 'SUPERADMIN';

        if($isSuperAdmin){
            $currentCompany = 'RBA - Average Score per Module Reports';
        } else {
            $currentCompany = (Company::where('id', Auth::user()?->company_id)->value('title') . ' - Average Score per Module Reports') ?? 'Average Score per Module Reports';
        }

        $fileName = 'Average_Score_per_Module_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new CourseAverageExport($courseIds, $search, $currentCompany),$fileName);
    }

    public function jobRoleCompletions(Request $request)
    {
        if ($request->isMethod('post')) {
            if (empty($request->role_id) && empty($request->search)) {
                return redirect()->back()->withErrors("Please select at least one filter");
            }
        }

        $query = JobRole::select('id', 'title');

        if (!empty($request->role_id)) {
            $query->whereIn('id', array_map('intval', $request->role_id));
        }

        if (!empty($request->search)) {
            $query->where('title', 'LIKE', "%{$request->search}%");
        }

        $jobRoles = $query->get();

        if ($jobRoles->isEmpty()) {
            return view('twill.completionReports.jobRoleCompletions', [
                'reportData' => [],
                'activeRoleId' => $request->role_id,
                'activeSearchTerm' => $request->search
            ]);
        }

        if(Auth::user()->role != 'SUPERADMIN'){
            $superAdmin = false;
        } else {
            $superAdmin = true;
        }

        if($superAdmin){
            $userStats = User::selectRaw("
                    job_role_id,
                    COUNT(*) as total_users,
                    SUM(CASE WHEN completion_status = 1 THEN 1 ELSE 0 END) as completed_users
                ")
                // SUM(CASE WHEN average_score_overall > 0.69 THEN 1 ELSE 0 END) as completed_users
                ->whereIn('job_role_id', $jobRoles->pluck('id'))
                ->groupBy('job_role_id')
                ->get()
                ->keyBy('job_role_id');
        } else {
            $userStats = User::selectRaw("
                    job_role_id,
                    COUNT(*) as total_users,
                    SUM(CASE WHEN completion_status = 1 THEN 1 ELSE 0 END) as completed_users
                ")
                // SUM(CASE WHEN average_score_overall > 0.69 THEN 1 ELSE 0 END) as completed_users
                ->where('company_id', Auth::user()?->company_id)
                ->whereIn('job_role_id', $jobRoles->pluck('id'))
                ->groupBy('job_role_id')
                ->get()
                ->keyBy('job_role_id');
        }

        $reportData = $jobRoles->map(function ($role) use ($userStats) {
            return [
                'id' => $role->id,
                'title' => $role->title,
                'registered_users' => $userStats[$role->id]->total_users ?? 0,
                'user_completed' => $userStats[$role->id]->completed_users ?? 0,
            ];
        });

        return view('twill.completionReports.jobRoleCompletions', [
            'reportData' => $reportData,
            'activeRoleId' => $request->role_id,
            'activeSearchTerm' => $request->search
        ]);
    }

    public function exportJobRoleCompletions(Request $request)
    {
        $query = JobRole::select('id', 'title');

        if (!empty($request->role_id)) {
            $query->whereIn('id', array_map('intval', $request->role_id));
        }

        if (!empty($request->search)) {
            $query->where('title', 'LIKE', "%{$request->search}%");
        }

        $jobRoles = $query->get();

        $isSuperAdmin = Auth::user()->role_value === 'SUPERADMIN';

        $userStatsQuery = User::selectRaw("
                job_role_id,
                COUNT(*) as total_users,
                SUM(CASE WHEN completion_status = 1 THEN 1 ELSE 0 END) as completed_users
            ")
            ->whereIn('job_role_id', $jobRoles->pluck('id'));

        if (!$isSuperAdmin) {
            $userStatsQuery->where('company_id', Auth::user()?->company_id);
        }

        $userStats = $userStatsQuery->groupBy('job_role_id')->get()->keyBy('job_role_id');

        $reportData = $jobRoles->map(function ($role) use ($userStats) {
            return [
                'id'               => $role->id,
                'title'            => $role->title,
                'registered_users' => $userStats[$role->id]->total_users ?? 0,
                'user_completed'   => $userStats[$role->id]->completed_users ?? 0,
            ];
        });

        if($isSuperAdmin){
            $currentCompany = 'RBA - Uptake per Job Role Reports';
        } else {
            $currentCompany = (Company::where('id', Auth::user()?->company_id)->value('title') . ' - Uptake per Job Role Reports') ?? 'RBA - Uptake per Job Role Reports';
        }

        $fileName = 'Uptake_per_Job_Role_Reports_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new JobRoleCompletionsExport($reportData, $currentCompany),$fileName);
    }
}
