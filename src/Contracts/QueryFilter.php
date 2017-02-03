<?php

namespace Larapac\QueryFilters\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Интерфейс фильтра (сервиса для применения набора правил фильтрации) для элоквент запроса.
 */
interface QueryFilter
{
    /**
     * Применение фильтра к запросу.
     * Applying this filter to query.
     *
     * @param Builder|QueryBuilder $query
     * @return Builder|QueryBuilder
     */
    public function apply($query);

    /**
     * Установка фильтра (массива правил).
     * Set filter rules.
     *
     * @param array|FilterRule[] $filter
     * @return void
     */
    public function setFilter(array $filter);
}
