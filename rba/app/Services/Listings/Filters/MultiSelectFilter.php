<?php

namespace App\Services\Listings\Filters;

use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Services\Listings\Filters\BasicFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * MultiSelectFilter
 *
 * Drop-in replacement for BasicFilter supporting multiple selected values.
 *
 * The filter key written to / read from the URL is always the ->queryString()
 * value (e.g. "course_id"), matching Twill's own convention.
 *
 * Usage in any controller:
 *
 *   use App\Services\Listings\Filters\MultiSelectFilter;
 *
 *   MultiSelectFilter::make()
 *       ->queryString('course_id')
 *       ->label('Course')
 *       ->options(collect(Course::published()->pluck('display_name', 'id')))
 *       ->apply(function (Builder $builder, array $values) {
 *           $builder->whereIn('course_id', $values);
 *       })
 */
class MultiSelectFilter extends BasicFilter
{
    // -------------------------------------------------------------------------
    // Core overrides
    // -------------------------------------------------------------------------

    /**
     * $appliedValue arrives as a string or array depending on what was in the URL.
     * We normalise to a clean non-empty array of strings before calling the closure.
     */
    public function applyFilter(Builder $builder): Builder
    {
        $raw = $this->appliedValue;

        if (empty($raw) || $raw === self::OPTION_ALL) {
            return $builder;
        }

        $values = is_array($raw) ? $raw : [$raw];
        $values = array_values(
            array_filter(array_map('strval', $values), fn ($v) => $v !== '' && $v !== self::OPTION_ALL)
        );

        if (!empty($values) && $closure = $this->apply) {
            $closure($builder, $values);
        }

        return $builder;
    }

    /**
     * No default for a multiselect — start with nothing checked.
     */
    public function getDefaultValue(): mixed
    {
        return [];
    }

    /**
     * Keep the 'List' suffix so Twill's Vue layer still renders a vselect wrapper
     * (which we replace in JS). Changing this key would break Twill's filter
     * resolution pipeline.
     */
    public function getKey(): string
    {
        return $this->queryString . 'List';
    }

    // -------------------------------------------------------------------------
    // Introspection API  (used by InjectTwillCustomScripts middleware)
    // -------------------------------------------------------------------------

    /**
     * Returns options as a plain array safe for json_encode:
     *   [ ['value' => '1', 'label' => 'Stress Management'], ... ]
     *
     * Unlike getOptions() this does NOT prepend the "All" sentinel, since our
     * custom dropdown treats zero-selections as "show all" implicitly.
     */
    public function getOptionsArray(): array
    {
        if ($this->options === null) {
            return [];
        }

        return $this->options
            ->reject(fn ($label, $value) => (string) $value === self::OPTION_ALL)
            ->map(fn ($label, $value) => [
                'value' => (string) $value,
                'label' => (string) $label,
            ])
            ->values()
            ->toArray();
    }
}