<?php

namespace App\Services\Listings\Filters;

use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Services\Listings\Filters\TwillBaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DateRangeFilter extends TwillBaseFilter
{
    protected mixed $appliedValue = null;

    protected string $type = 'text';

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function withFilterValue(mixed $value): static
    {
        $this->appliedValue = $value;

        return $this;
    }

    public function getDefaultValue(): mixed
    {
        return null;
    }

    public function getKey(): string
    {
        return $this->queryString;
    }

    public function applyFilter(Builder $builder): Builder
    {
        if ($this->appliedValue && $closure = $this->apply) {
            $closure($builder, $this->appliedValue);
        }

        return $builder;
    }

    public function getOptions(ModuleRepository $repository): Collection
    {
        return collect();
    }
}