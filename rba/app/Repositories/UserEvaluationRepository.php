<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use A17\Twill\Repositories\ModuleRepository;
use Illuminate\Database\Eloquent\Builder;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Facades\TwillPermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\UserEvaluation;
use Carbon\Carbon;

class UserEvaluationRepository extends ModuleRepository
{
    protected array $multiSelectColumns = [
        'course_id'    => 'course_id',
        'company_id'   => 'company_id',
    ];

    protected array $dateColumns = [
        'created_at'     => 'created_at',
    ];

    public function __construct(UserEvaluation $model)
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

        $this->applyRequestMultiSelectFilters($query);

        $this->applyDateRangeFilter($query, $this->dateColumns);

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

        if (
            TwillPermissions::enabled() &&
            (
                TwillPermissions::getPermissionModule(getModuleNameByModel($this->model)) ||
                method_exists($this->model, 'scopeAccessible')
            )
        ) {
            $query = $query->accessible();
        }

        if (Auth::user()->role_value === 'Company HR') {
            $query = $query->where('company_id', Auth::user()->company_id);
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
