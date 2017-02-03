<?php

namespace Larapac\QueryFilters;

use Larapac\QueryFilters\Contracts\FilterRule as FilterRuleContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FilterRule implements FilterRuleContract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $parameter;

    /**
     * @var mixed
     */
    protected $modifier;

    /**
     * FilterRule constructor.
     * @param string $name
     * @param mixed $parameter
     * @param mixed $modifier
     */
    public function __construct($name, $parameter, $modifier)
    {
        $this->name = $name;
        $this->parameter = $parameter;
        $this->modifier = $modifier;
    }

    /**
     * @param Builder|QueryBuilder $query
     * @return Builder|QueryBuilder
     */
    public function apply($query)
    {
        return $query;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function parameter()
    {
        return $this->parameter;
    }

    /**
     * @return mixed
     */
    public function modifier()
    {
        return $this->modifier;
    }
}
