<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use Illuminate\Database\Eloquent\Builder;
use A17\Twill\Services\Forms\Fieldset;
use App\Exports\UserEvaluationsExport;
use A17\Twill\Services\Forms\Columns;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use A17\Twill\Services\Forms\Form;
use Illuminate\Http\JsonResponse;
use App\Models\UserEvaluation;
use App\Models\Company;
use App\Models\Course;
use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Repeater;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class UserEvaluationController extends BaseModuleController
{
    protected $moduleName = 'userEvaluations';

    /**
     * Name of the index column to use as name column.
     *
     * @var string
     */
    protected $titleColumnKey = 'username';

    /**
     * Label of the index column to use as name column.
     *
     * @var string
     */
    protected $titleColumnLabel = 'Username';

    /**
     * Label of the title field in forms.
     *
     * @var string
     */
    protected $titleFormLabel = 'Username';

        /**
     * Array of customizable label translation keys.
     *
     * @var array
     */
    protected $labels = [
        'published' => 'Published',
        'draft' => 'Archived',
        'listing' => [
            'filter' => [
                'published' => 'Published',
                'draft' => 'Archived',
            ],
        ],
    ];

    /**
     * Removes the "Create" button on the listing page.
     */
    protected function disableCreate(): void
    {
        $this->indexOptions['create'] = false;
    }

    protected $filters = [
        'company_id'      => 'company_id',
        'course_id'       => 'course_id',
        'created_at'      => 'created_at',
    ];

    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disableCreate();
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();

        if ($this->getIndexOption('publish')) {
            $columns->add(
                PublishStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.published'))
                    ->sortable()
                    ->optional()
            );
        }

        $columns->add(Text::make()->field('username')->title('Username'));
        $columns->add(Text::make()->field('course_title_value')->title('Course'));
        $columns->add(Text::make()->field('date_updated')->title('Submitted At')->sortable());
        $columns->add(Text::make()->field('question1')->title('Question 1'));
        $columns->add(Text::make()->field('answer1')->title('Answer 1'));
        $columns->add(Text::make()->field('question2')->title('Question 2'));
        $columns->add(Text::make()->field('answer2')->title('Answer 2'));
        $columns->add(Text::make()->field('question3')->title('Question 3'));
        $columns->add(Text::make()->field('answer3')->title('Answer 3'));
        $columns->add(Text::make()->field('question3')->title('Question 3'));
        $columns->add(Text::make()->field('answer3')->title('Answer 3'));
        $columns->add(Text::make()->field('question4')->title('Question 4'));
        $columns->add(Text::make()->field('answer4')->title('Answer 4'));
        $columns->add(Text::make()->field('question5')->title('Question 5'));
        $columns->add(Text::make()->field('answer5')->title('Answer 5'));

        return $columns;
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = Form::make();

        $form->addFieldset(
            Fieldset::make()->title('Q & A')->id('qna')->fields([
                Columns::make()->left([Input::make()->name('question1')->readOnly()])->right([Input::make()->name('answer1')->readOnly()]),
                Columns::make()->left([Input::make()->name('question2')->readOnly()])->right([Input::make()->name('answer2')->readOnly()]),
                Columns::make()->left([Input::make()->name('question3')->readOnly()])->right([Input::make()->name('answer3')->readOnly()]),
                Columns::make()->left([Input::make()->name('question4')->readOnly()])->right([Input::make()->name('answer4')->readOnly()]),
                Columns::make()->left([Input::make()->name('question5')->readOnly()])->right([Input::make()->name('answer5')->readOnly()]),
                Columns::make()->left([Input::make()->name('question6')->readOnly()])->right([Input::make()->name('answer6')->readOnly()]),
                Columns::make()->left([Input::make()->name('question7')->readOnly()])->right([Input::make()->name('answer7')->readOnly()]),
                Columns::make()->left([Input::make()->name('question8')->readOnly()])->right([Input::make()->name('answer8')->readOnly()]),
                Columns::make()->left([Input::make()->name('question9')->readOnly()])->right([Input::make()->name('answer9')->readOnly()]),
                Columns::make()->left([Input::make()->name('question10')->readOnly()])->right([Input::make()->name('answer10')->readOnly()]),
            ])
        );

        return $form;
    }

    public function getSideFieldsets(TwillModelContract $model): Form
    {
        $form = Form::make();
        $form->add(Input::make()->name('username')->label('Username')->readOnly());
        $form->add(Input::make()->name('course_title_value')->label('Course')->readOnly());
        return $form;
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

    public function additionalTableActions(): array
    {
        return [
            'exportAction' => [
                'name'    => 'Export',
                'variant' => 'primary',
                'size'    => 'small',
                'link'    => route('twill.export.userEvaluations'),
                'target'  => '',
                'type'    => 'a',
            ],
        ];
    }

    public function export(): BinaryFileResponse
    {
        $query = UserEvaluation::query()->orderBy('created_at', 'desc');

        if (
            auth()->guard('twill_users')->user()->role_value === 'Company HR' ||
            auth()->guard('twill_users')->user()->is_company_admin === 1 ||
            auth()->guard('twill_users')->user()->role_id === 3
        )
        {
            $query->where('company_id', Auth::user()->company_id);
        }

        $referer = $this->request->header('referer');
        if ($referer) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $queryParams);

            if (!empty($queryParams['filter'])) {
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

                $multiSelectColumns = ['course_id','company_id'];

                foreach ($multiSelectColumns as $col) {
                    if (!empty($filter[$col]) && $filter[$col] !== 'all') {
                        $query->whereIn($col, (array) $filter[$col]);
                    }
                }
            }
        }

        $fileName = 'Course_Evaluations_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';

        return Excel::download(new UserEvaluationsExport($query), $fileName);
    }

    protected function indexData($request)
    {
        $CompanyhrRoles = ['COMPANYHR'];
        $completionStatuses = collect(['Completed' =>'Completed', 'Failed' => 'Failed', 'In Progress'=>'In Progress']);
        $coursesList = Course::published()->orderBy('short_name')->get(['id', 'display_name as title']);

        $user = auth()->guard('twill_users')->user();

        if ($user && in_array($user->role_value, $CompanyhrRoles)) {

            $companyList = Company::published()->orderBy('title')->where('id', $user->company_id)->get(['id', 'title']);

        } else {
            $companyList = Company::published()->orderBy('title')->get(['id', 'title']);
        }

        return [
            'company_idList' =>  $companyList,
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

        $view = $route;

        return View::make($view, $indexData + ['repository' => $this->repository])
            ->with(['formBuilder' => $form->toFrontend(isCreate: true)]);
    }


    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        return $this->respondWithSuccess(('Ooops! You can preview edits, but not edit them.'));
    }
}
