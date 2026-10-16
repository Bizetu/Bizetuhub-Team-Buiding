<?php

namespace App\Repositories;
use A17\Twill\Models\Contracts\TwillModelContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use A17\Twill\Models\Behaviors\Sortable;
use Illuminate\Support\Collection;
use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Carbon\Carbon;

class MessageRepository extends ModuleRepository
{
    use HandleBlocks, HandleRevisions;

    public function __construct(Message $model)
    {
        $this->model = $model;
    }

    public function update(int|string $id, array $fields): TwillModelContract
    {
        return DB::transaction(function () use ($id, $fields) {
            $model = $this->model->findOrFail($id);

            //Handle cases where there were multiselect values but later removed
            $multiSelectFields = [
                'user_ids',
                'target_admins',
                'job_role_ids',
                'department_ids',
                'branch_ids',
                'audience_ids',
                'course_ids',
            ];

            $fields = array_merge(
                $fields,
                array_fill_keys($multiSelectFields, []),
                $fields
            );

            $original_fields = $fields;

            $this->beforeSave($model, $fields);
            

            $fields = $this->prepareFieldsBeforeSave($model, $fields);
            // dd($fields);
            $model->fill(Arr::except($fields, $this->getReservedFields()));


            $model->save();

            $this->afterSaveOriginalData($model, $original_fields);

            $this->afterSave($model, $fields);

            return $model->fresh();
        }, 3);
    }

     protected array $multiSelectColumns = [
        'message_type'          => 'message_type',
        'company_id'            => 'company_id',      
    ];

    protected array $jsonColumns = [
        'branch_ids'             => 'branch_ids',
        'course_ids'             => 'course_ids',
        'department_ids'         => 'department_ids',
        'job_role_ids'           => 'job_role_ids',
        'audience_ids'            => 'audience_ids',  
    ];

    public function get(
        array $with = [],
        array $scopes = [],
        array $orders = [],
        int $perPage = 20,
        bool $forcePagination = false,
        array $appliedFilters = []
    ): LengthAwarePaginator|Collection {
        $query = $this->model->with($with);
        // dd($scopes);
        $request = request();
        $query = $query->accessible();
        $this->applyRequestMultiSelectFilters($query);
        $query = $this->filter($query, $scopes);
        $query = $this->order($query, $orders);

        $user = Auth::user();
        $isCompanyHR = $user->role_id === 3 || $user->is_company_admin === 1 || $user->role_value === 'Company HR';

        if ($isCompanyHR) {
            $query->where('company_id', Auth::user()->company_id);
        }
        // dd($appliedFilters);

        // foreach ($appliedFilters as $filter) {
        //     $query = $filter->applyFilter($query);
        // }

        //appply date range filter
        // dd($query->get());
        $this->applyDateRangeFilter($query);

        if (! $forcePagination && $this->model instanceof Sortable) {
            return $query->ordered()->get();
        }

        if ($perPage == -1) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    public function applyRequestMultiSelectFilters(Builder $query): void
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

        foreach ($this->jsonColumns as $filterKey => $column) {
            $value = $filter[$filterKey] ?? null;

            if (empty($value) || $value === 'all') {
                continue;
            }

            $values = array_filter(
                array_map('strval', (array) $value),
                fn ($v) => $v !== '' && $v !== 'all'
            );

            if (!empty($values)) {
                $query->where(function ($q) use ($column, $values) {
                    foreach ($values as $value) {
                        $q->orWhereJsonContains($column, (string) $value);
                    }
                });
            }
        }        
    }

    public function applyDateRangeFilter(Builder $query): void
    {
        $filterParam = json_decode(request()->get('filter'), true) ?? [];

        if (!empty($filterParam['send_date'])) {
            $range = explode(' - ', $filterParam['send_date']);

            if (count($range) == 2) {
                $start = Carbon::parse(trim($range[0]))->startOfDay();
                $end = Carbon::parse(trim($range[1]))->endOfDay();
                $query->whereBetween('send_date', [$start, $end]);
            }
        }
    }

}
