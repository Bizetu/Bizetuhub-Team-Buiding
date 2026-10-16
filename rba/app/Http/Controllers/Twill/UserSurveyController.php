<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use Illuminate\Database\Eloquent\Builder;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Columns;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserSurveysExport;
use A17\Twill\Services\Forms\Form;
use Illuminate\Http\JsonResponse;
use App\Models\UserSurvey;
use App\Models\Department;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Survey;
use App\Models\Branch;


class UserSurveyController extends BaseModuleController
{
    protected $moduleName = 'userSurveys';
    protected $defaultOrders = ['created_at' => 'desc'];
    protected $perPage = 200;
    protected $titleColumnKey = 'survey_title';
    protected $titleColumnLabel = 'Survey';
    protected $indexWith = ['survey', 'user'];

    protected function setUpController(): void
    {
        $this->disableCreate();
        //$this->disableEdit();
        $this->disableSortable();

        $this->setSearchColumns([]);

        $this->setSearchQuery(function ($query, $searchString, $translatedAttributes) {
            $query->where(function ($q) use ($searchString) {
                $q->where('name', 'LIKE', "%{$searchString}%")
                  ->orWhere('email', 'LIKE', "%{$searchString}%")
                  ->orWhereHas('survey', function ($surveyQuery) use ($searchString) {
                      $surveyQuery->where('title', 'LIKE', "%{$searchString}%");
                  });
            });
        });
    }

    protected function formatAnswer($value): string
    {
        if (is_array($value)) {
            return implode(', ', $value);
        }

        if (is_scalar($value)) {
            if (is_string($value) && $this->isJson($value)) {
                $decoded = json_decode($value, true);

                if (is_array($decoded)) {
                    return implode(', ', $decoded);
                }

                return (string) $decoded;
            }

            return (string) $value;
        }

        return '';
    }

    protected function isJson($value): bool
    {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        for ($i = 1; $i <= 30; $i++) {
            $answerKey = "answer_{$i}";
            $model->$answerKey = $this->formatAnswer($model->$answerKey);
        }

        $form->addFieldset(
            Fieldset::make()->title('Survey Response')->id('survey_response_details')->fields([
                Columns::make()->left([Input::make()->name('email')->readOnly()])->right([Input::make()->name('name')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_1')->readOnly()])->right([Input::make()->name('answer_1')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_2')->readOnly()])->right([Input::make()->name('answer_2')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_3')->readOnly()])->right([Input::make()->name('answer_3')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_4')->readOnly()])->right([Input::make()->name('answer_4')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_5')->readOnly()])->right([Input::make()->name('answer_5')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_6')->readOnly()])->right([Input::make()->name('answer_6')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_7')->readOnly()])->right([Input::make()->name('answer_7')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_8')->readOnly()])->right([Input::make()->name('answer_8')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_9')->readOnly()])->right([Input::make()->name('answer_9')->readOnly()]),
                Columns::make()->left([Input::make()->name('question_10')->readOnly()])->right([Input::make()->name('answer_10')->readOnly()]),
            ])
        );

        return $form;
    }

    public function getIndexTableColumns(): TableColumns
    {
        $tableColumns = TableColumns::make();
        if (auth()->guard('twill_users')->user()->role_id === 1 || auth()->guard('twill_users')->user()->role_value === "SUPERADMIN" || auth()->guard('twill_users')->user()->is_superadmin === 1) {
            $tableColumns->add(
                PublishStatus::make()->title(twillTrans('twill::lang.listing.columns.published'))->sortable()->optional()
            );
        }

        $tableColumns->add(Text::make()->field($this->titleColumnKey)->linkToEdit()->sortable());
        $tableColumns->add(Text::make()->field('name')->title('Name')->sortable());
        $tableColumns->add(Text::make()->field('email')->title('Email')->sortable());
        $tableColumns->add(Text::make()->field('branch_title')->title('Branch')->sortable());
        $tableColumns->add(Text::make()->field('company_title')->title('Company')->sortable());
        $tableColumns->add(Text::make()->field('department_title')->title('Department')->sortable());
        $tableColumns->add(Text::make()->field('job_role_title')->title('Job Role')->sortable());
        $tableColumns->add(Text::make()->field('date_created')->title('Submitted')->sortable());
        $tableColumns->add(Text::make()->field('question_1')->title('Question 1')->sortable());
        $tableColumns->add(Text::make()->field('answer_1')->title('Answer 1')->sortable());
        $tableColumns->add(Text::make()->field('question_2')->title('Question 2')->sortable());
        $tableColumns->add(Text::make()->field('answer_2')->title('Answer 2')->sortable());
        $tableColumns->add(Text::make()->field('question_3')->title('Question 3')->sortable());
        $tableColumns->add(Text::make()->field('answer_3')->title('Answer 3')->sortable());
        $tableColumns->add(Text::make()->field('question_4')->title('Question 4')->sortable());
        $tableColumns->add(Text::make()->field('answer_4')->title('Answer 4')->sortable());
        $tableColumns->add(Text::make()->field('question_5')->title('Question 5')->sortable());
        $tableColumns->add(Text::make()->field('answer_5')->title('Answer 5')->sortable());
        $tableColumns->add(Text::make()->field('question_6')->title('Question 6')->sortable());
        $tableColumns->add(Text::make()->field('answer_6')->title('Answer 6')->sortable());
        $tableColumns->add(Text::make()->field('question_7')->title('Question 7')->sortable());
        $tableColumns->add(Text::make()->field('answer_7')->title('Answer 7')->sortable());
        $tableColumns->add(Text::make()->field('question_8')->title('Question 8')->sortable());
        $tableColumns->add(Text::make()->field('answer_8')->title('Answer 8')->sortable());
        $tableColumns->add(Text::make()->field('question_9')->title('Question 9')->sortable());
        $tableColumns->add(Text::make()->field('answer_9')->title('Answer 9')->sortable());
        $tableColumns->add(Text::make()->field('question_10')->title('Question 10')->sortable());
        $tableColumns->add(Text::make()->field('answer_10')->title('Answer 10')->sortable());

        return $tableColumns;
    }

    protected function getMaxQuestionNumber(): int
    {
        $maxQuestions = 0;

        $query = UserSurvey::select([
            'id',
            'question_1', 'question_2', 'question_3', 'question_4', 'question_5',
            'question_6', 'question_7', 'question_8', 'question_9', 'question_10',
            'question_11', 'question_12', 'question_13', 'question_14', 'question_15',
            'question_16', 'question_17', 'question_18', 'question_19', 'question_20',
            'question_21', 'question_22', 'question_23', 'question_24', 'question_25',
            'question_26', 'question_27', 'question_28', 'question_29', 'question_30',
        ]);

        if (Auth::user()->role_id === 3) {
            $query->whereHas('user', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        }

        $surveys = $query->get();

        foreach ($surveys as $survey) {
            for ($i = 1; $i <= 30; $i++) {
                if (!empty($survey->{"question_{$i}"})) {
                    $maxQuestions = max($maxQuestions, $i);
                }
            }
        }

        return $maxQuestions;
    }

    protected function indexItemData(TwillModelContract $item)
    {
        $data = [];

        for ($i = 1; $i <= 30; $i++) {
            $answerKey = "answer_{$i}";
            if ($item->$answerKey) {
                $answer = trim($item->$answerKey, '"');
                if (strlen($answer) > 50) {
                    $answer = substr($answer, 0, 47) . '...';
                }
                $data[$answerKey] = $answer;
            }
        }

        return $data;
    }

    public function filters(): TableFilters
    {
        $isHR = auth()->guard('twill_users')->user()->is_hr;

        $tableFilters = TableFilters::make();

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('survey_id')
                ->label('Survey')
                ->options(collect(Survey::published()->orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('survey_id', $v))
        );

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('branch_id')
                ->label('Branch')
                ->options(collect(Branch::orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('branch_id', $v))
        );

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('department_id')
                ->label('Department')
                ->options(collect(Department::orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('department_id', $v))
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
                'link'    => route('twill.userSurveys.index'),
                'target'  => '',
                'type'    => 'a',
            ],
            'exportAction' => [
                'name'    => 'Export',
                'variant' => 'primary',
                'size'    => 'small',
                'link'    => route('twill.export.userSurveys'),
                'target'  => '',
                'type'    => 'a',
            ],
        ];
    }

    public function export()
    {
        $query = UserSurvey::query()->orderBy('created_at', 'desc');

        if (!auth()->guard('twill_users')->user()){
            return back()->withErrors('Please log in in another tab and try again.');
        }
        if (
            auth()->guard('twill_users')->user()->role_value === 'Company HR' ||
            auth()->guard('twill_users')->user()->is_company_admin === 1 ||
            auth()->guard('twill_users')->user()->role_id === 3
        ) {
            $query->where('company_id', Auth::user()->company_id);
        }

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

                $multiSelectColumns = ['survey_id','branch_id','department_id','job_role_id','company_id'];

                foreach ($multiSelectColumns as $col) {
                    if (!empty($filter[$col]) && $filter[$col] !== 'all') {
                        $query->whereIn($col, (array) $filter[$col]);
                    }
                }
            }
        }

        $fileName = 'User_Surveys_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';

        return Excel::download(new UserSurveysExport($query), $fileName);
    }

    /**
     * Override update method to prevent editing survey responses
     */
    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        return $this->respondWithError('Survey responses cannot be edited. This action is not permitted.');
    }

    /**
     * Override store method to prevent creating survey responses from admin
     */
    public function store($parentModuleId = null): JsonResponse
    {
        return $this->respondWithError('Survey responses can only be created by users submitting surveys. This action is not permitted.');
    }
}
