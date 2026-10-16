<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\ModuleRepository;
use Illuminate\Database\Eloquent\Builder;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Facades\TwillPermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\UserSurvey;
use Throwable;

class UserSurveyRepository extends ModuleRepository
{
    use HandleBlocks, HandleRevisions;

    protected array $multiSelectColumns = [
        'survey_id'    => 'survey_id',
        'branch_id'    => 'branch_id',
        'department_id'=> 'department_id',
        'job_role_id'  => 'job_role_id',
        'company_id'   => 'company_id',
    ];

    public function __construct(UserSurvey $model)
    {
        $this->model = $model;
    }

    public function get(
        array $with = ['user'],
        array $scopes = [],
        array $orders = [],
        int $perPage = 20,
        bool $forcePagination = false,
        array $appliedFilters = []
    ): LengthAwarePaginator|Collection {

        $user = Auth::user();
        $isCompanyHR = $user->role_id === 3 || $user->is_company_admin === 1 || $user->role_value === 'Company HR';

        if ($isCompanyHR) {
            $query = $this->model->where('company_id', Auth::user()->company_id)->with($with);
        } else {
            $query = $this->model->with($with);
        }

        $query = $this->filter($query, $scopes);
        $query = $this->order($query, $orders);

        foreach ($appliedFilters as $filter) {
            $query = $filter->applyFilter($query);
        }

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
            $filter = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
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
}
