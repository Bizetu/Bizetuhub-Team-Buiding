<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\CompletionReport;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Contracts\TwillModelContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use A17\Twill\Facades\TwillPermissions;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CompletionReportRepository extends ModuleRepository
{

    protected array $multiSelectColumns = [
        'completion_status'     => 'completion_status',
        'course_id'             => 'course_id',
        'branch_id'             => 'branch_id',
        'department_id'         => 'department_id',
        'job_role_id'           => 'job_role_id',
        'company_id'            => 'company_id',
    ];

    protected array $dateColumns = [
        'enrollment_date'     => 'enrollment_date',
        'completion_date'     => 'completion_date',
    ];

    public function __construct(CompletionReport $model)
    {
        $this->model = $model;
    }

    public function get(
        array $with = [],
        array $scopes = [],
        array $orders = [],
        int $perPage = 20,
        bool $forcePagination = false,
        array $appliedFilters = []
    ): LengthAwarePaginator|Collection {
        $query = $this->model->with($with);
        //Applying multiselect columns filters
        $this->applyRequestMultiSelectFilters($query);
        //Applying daterange filters
        $this->applyDateRangeFilter($query, $this->dateColumns);

        //Applying search
        $filter    = json_decode(request()->query('filter'), true);
        if(isset($filter['search'])){
                $search = $filter['search'];

                $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")

                    ->orWhereHas('jobRole', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })

                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })

                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })

                    ->orWhereHas('branch', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })

                    ->orWhereHas('courseRelationship', function ($q) use ($search) {
                        $q->where('display_name', 'like', "%{$search}%");
                    });
                });
        }


        $query = $this->filter($query, $scopes);
        $query = $this->order($query, $orders);

        $user = Auth::user();
        $isCompanyHR = $user->role_id === 3 || $user->is_company_admin === 1 || $user->role_value === 'Company HR';

        if ($isCompanyHR) {
            $query->where('company_id', Auth::user()->company_id);
        }

        // foreach ($appliedFilters as $filter) {
        //     $query = $filter->applyFilter($query);
        // }

        if (! $forcePagination && $this->model instanceof Sortable) {
            return $query->ordered()->get();
        }

        if ($perPage == -1) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    public function getCountByStatusSlug(string $slug, array $scope = []): int
    {
        $query = $this->model->where($scope);

        $user = Auth::user();
        $isCompanyHR = $user->role_id === 3 || $user->is_company_admin === 1 || $user->role_value === 'Company HR';

        if ($isCompanyHR) {
            $query->where('company_id', Auth::user()->company_id);
        }

        if (
            TwillPermissions::enabled() &&
            (
                TwillPermissions::getPermissionModule(getModuleNameByModel($this->model)) ||
                method_exists($this->model, 'scopeAccessible')
            )
        ) {
            $query = $query->accessible();
        }

        $this->applyRequestMultiSelectFilters($query);

        switch ($slug) {
            case 'all':
                return $query->count();
            case 'published':
                return $query->published()->count();
            case 'draft':
                return $query->draft()->count();
            case 'trash':
                return $query->onlyTrashed()->count();
        }

        foreach ($this->traitsMethods(__FUNCTION__) as $method) {
            if (($count = $this->$method($slug, $scope)) !== false) {
                return $count;
            }
        }

        return 0;
    }

    protected function applyRequestMultiSelectFilters(Builder $query): void
    {
        $raw = request()->query('filter');
        if (empty($raw)) {
            return;
        }

        try {
            // The URL stores raw JSON (single-pass encoded by URLSearchParams).
            // request()->query() already URL-decoded it once, so JSON-decode directly.
            $filter = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return;
        }

        foreach ($this->multiSelectColumns as $filterKey => $column) {
            $value = $filter[$filterKey] ?? null;

            if (empty($value) || $value === 'all') {
                continue;
            }

            $values = array_filter(
                array_map('strval', (array) $value),
                fn ($v) => $v !== '' && $v !== 'all'
            );

            if (!empty($values)) {
                $query->whereIn($column, $values);
            }
        }

    }

    public function applyDateRangeFilter(Builder $query, $columns): void
    {
        $filterParam = json_decode(request()->get('filter'), true) ?? [];

        foreach ($columns as $column){
            if (!empty($filterParam[$column])) {
                $range = explode(' - ', $filterParam[$column]);

                if (count($range) == 2) {
                    $start = Carbon::parse(trim($range[0]))->startOfDay();
                    $end = Carbon::parse(trim($range[1]))->endOfDay();
                    $query->whereBetween($column, [$start, $end]);
                }
            }
        }


    }
}
