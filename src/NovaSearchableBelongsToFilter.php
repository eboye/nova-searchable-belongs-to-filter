<?php

namespace Suenerds\NovaSearchableBelongsToFilter;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class NovaSearchableBelongsToFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'nova-searchable-belongs-to-filter';

    public $filterBy;

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where($this->filterBy, $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }

    public function name()
    {
        return __('Filter by ' . $this->meta()['fieldAttribute']);
    }

    public function fieldAttribute($attribute)
    {
        $this->withMeta([
            'fieldAttribute' => $attribute,
        ]);

        return $this;
    }

    public function filterBy($attribute)
    {
        $this->filterBy = $attribute;

        return $this;
    }
}