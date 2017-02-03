<?php

namespace Larapac\QueryFilters;

use Larapac\QueryFilters\Contracts\FilterRule;
use Larapac\QueryFilters\Contracts\RequestTranspiler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

/**
 * Базовая реализация фильра на основании http запроса.
 *
 * Заполение набора правил используя транспилер;
 * При отсутствии правил в запросе возможность применить набор правил по умолчанию;
 * Применение основных правил на основании их имени:
 *  - is
 *  - has
 *  - sort
 *  - search
 *  - остальные трактуются как строгий поиск по полю
 * Контроль допустимости правил (белый список);
 *
 */
abstract class SimpleFilter extends AbstractFilter
{
    /**
     * @var string|string[]
     */
    protected $base = '';

    /**
     * @var string[]
     */
    protected $sortable = [];

    /**
     * @var string[]
     */
    protected $searchable = [];

    /**
     * @var RequestTranspiler
     */
    protected $transpiler;

    /**
     * @var Request
     */
    protected $request;

    /**
     * SimpleFilter constructor.
     * @param Request $request
     * @param RequestTranspiler $transpiler
     */
    public function __construct(Request $request, RequestTranspiler $transpiler)
    {
        $this->transpiler = $transpiler;
        $this->request = $request;

        $this->setFilter($transpiler->toFilter($request));

        $this->setFilterFromDefault();
    }

    /**
     *
     */
    protected function setFilterFromDefault()
    {
        if ([] === $this->filter) {
            $this->setFilter($this->defaultFilter());
        }
    }

    /**
     * @return array|FilterRule[]
     */
    protected function defaultFilter()
    {
        return [];
    }

    /**
     * @param string|FilterRule $name
     * @param mixed|null $parameter
     * @param mixed|null $modifier
     * @return bool
     */
    public function contains($name, $parameter = null, $modifier = null)
    {
        $compare_by_class = $name instanceof FilterRule;
        foreach ($this->filter as $rule) {
            if ($compare_by_class && $rule == $name) {
                return true;
            }
            if (!$compare_by_class) {
                $equivalent = $rule->name() === $name
                    && (null === $parameter || $rule->parameter() === $parameter
                        && (null === $modifier || $rule->modifier() === $modifier)
                    );
                if ($equivalent) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function getOverridingApplying(FilterRule $rule)
    {
        $name = (string) $rule->name();
        if (in_array($name, $this->searchable, true)) {
            return "applySearchBy";
        }

        return parent::getOverridingApplying($rule);
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param FilterRule $rule
     * @return Builder|QueryBuilder
     */
    protected function applySearchBy($query, FilterRule $rule)
    {
        if (is_array($rule->parameter())) {
            return $query->whereIn($rule->name(), $rule->parameter());
        }

        return $query->where($rule->name(), $rule->parameter());
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param FilterRule $rule
     * @return Builder|QueryBuilder
     */
    protected function applySearch($query, FilterRule $rule)
    {
        if (empty($this->base) || empty($rule->parameter())) {
            return $query;
        }

        $search = str_replace(['%', '_'], ['\%', '\_'], $rule->parameter());
        return $query->where(function ($q) use ($search) {
            foreach ((array) $this->base as $field) {
                if ('id' === $field && is_numeric($search)) {
                    $field = $q->getModel() ? $q->getModel()->getQualifiedKeyName() : 'id';
                    $q->where($field, $search);
                } elseif ('id' !== $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            }
        });
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param FilterRule $rule
     * @return Builder|QueryBuilder
     */
    protected function applySort($query, FilterRule $rule)
    {
        $field = $rule->parameter();
        if (!in_array($field, $this->sortable, true)) {
            return $query;
        }

        return $query->orderBy($field, mb_strtoupper((string) $rule->modifier()) === 'ASC' ? 'ASC' : 'DESC');
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param FilterRule $rule
     * @return Builder|QueryBuilder
     */
    protected function applyIs($query, FilterRule $rule)
    {
        $field = $rule->parameter();
        if (!in_array($field, $this->searchable, true)) {
            return $query;
        }

        return $query->where($field, true);
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param FilterRule $rule
     * @return Builder|QueryBuilder
     */
    protected function applyHas($query, FilterRule $rule)
    {
        $related = $rule->parameter();
        if (!in_array($related, $this->searchable, true)) {
            return $query;
        }

        return $query->has($related, true);
    }
}
