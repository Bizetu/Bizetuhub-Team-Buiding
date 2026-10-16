<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\ModuleRepository;
use Illuminate\Database\Eloquent\Builder;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Facades\TwillPermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\UserLicense;
use Carbon\Carbon;

class UserLicenseRepository extends ModuleRepository
{
    protected array $multiSelectColumns = [
        'course_id'    => 'course_id',
        'branch_id'    => 'branch_id',
        'department_id'=> 'department_id',
        'job_role_id'  => 'job_role_id',
        'company_id'   => 'company_id',
    ];

    protected array $dateColumns = [
        'created_at'     => 'created_at',
    ];

    public function __construct(UserLicense $model)
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

                    ->orWhereHas('course', function ($q) use ($search) {
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

    /**
     * Overrides the Twill base so the counts respect:
     *  1. Company HR scoping (same as the listing query).
     *  2. Any active MultiSelectFilters parsed from the current request URL,
     *     so the tab counts always match the visible rows.
     *
     * The $scope array (passed by Twill for search/basic filter scopes) is
     * applied first via where($scope), then our additional constraints follow.
     */
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

    /**
     * Reads the ?filter= JSON from the current HTTP request and applies
     * whereIn constraints for every key listed in $multiSelectColumns.
     *
     * This mirrors exactly what MultiSelectFilter::applyFilter() does in the
     * listing query, keeping status-tab counts in sync with the visible rows.
     *
     * Single values (backward compat with BasicFilter URLs) are cast to arrays
     * via (array) so whereIn handles them correctly.
     */
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