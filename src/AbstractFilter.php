<?php

namespace Larapac\QueryFilters;

use Larapac\QueryFilters\Contracts\FilterRule;
use Larapac\QueryFilters\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use InvalidArgumentException;

/**
 * Основной класс применения фильтра.
 *
 * Контроль установки фильтра и его хранение;
 * Механизм применения фильтра как последовательное применение всех правил
 *  с перекрытием их действия самим классом фильтра;
 *
 * Данный фильтр не связан с http запросом
 */
abstract class AbstractFilter implements QueryFilter
{
    /**
     * @var FilterRule[]
     */
    protected $filter;

    /**
     * @param Builder|QueryBuilder $query
     * @return Builder|QueryBuilder
     */
    public function apply($query)
    {
        foreach ((array) $this->filter as $rule) {
            $query = $this->applyRule($query, $rule);
        }

        return $query;
    }

    /**
     * @param array|FilterRule[] $filter
     */
    public function setFilter(array $filter)
    {
        foreach ($filter as $rule) {
            if (!$rule instanceof FilterRule) {
                throw new InvalidArgumentException('Filter must be array of ' . FilterRule::class);
            }
        }

        $this->filter = $filter;
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param FilterRule $rule
     * @return Builder|QueryBuilder
     */
    protected function applyRule($query, FilterRule $rule)
    {
        if ($method = $this->getOverridingApplying($rule)) {
            return $this->{$method}($query, $rule);
        }

        return $rule->apply($query);
    }

    /**
     * @param FilterRule $rule
     * @return null|string
     */
    protected function getOverridingApplying(FilterRule $rule)
    {
        $name = (string) $rule->name();
        if (is_array($rule->parameter())) {
            $val = collect($rule->parameter())->flatten()->implode('_');
        } else {
            $val = (string) $rule->parameter();
        }

        $method = camel_case("apply_{$name}_{$val}");
        if (method_exists($this, $method)) {
            return $method;
        }

        $method = camel_case("apply_{$name}");
        if (method_exists($this, $method)) {
            return $method;
        }

        return null;
    }
}
