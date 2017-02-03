<?php

namespace Larapac\QueryFilters\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface FilterRule
{
    /**
     * @param Builder|QueryBuilder $query
     * @return Builder|QueryBuilder
     */
    public function apply($query);

    /**
     * @return string
     */
    public function name();

    /**
     * @return mixed
     */
    public function parameter();

    /**
     * @return mixed
     */
    public function modifier();
}
