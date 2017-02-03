<?php

namespace Larapac\QueryFilters;

use Larapac\QueryFilters\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait Filterable
{
    /**
     * Apply all filters to given query
     *
     * @param Builder|QueryBuilder $query
     * @param QueryFilter $filter
     * @return Builder|QueryBuilder
     */
    public function scopeFiltered($query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
