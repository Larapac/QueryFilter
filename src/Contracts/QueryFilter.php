<?php

namespace Larapac\QueryFilters\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface QueryFilter
{
    /**
     * Applying this filter to query.
     *
     * @param Builder|QueryBuilder $query
     * @return Builder|QueryBuilder
     */
    public function apply($query);

    /**
     * Set filter rules.
     *
     * @param array|FilterRule[] $filter
     * @return void
     */
    public function setFilter(array $filter);
}
