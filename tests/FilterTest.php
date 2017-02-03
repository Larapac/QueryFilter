<?php

use Larapac\QueryFilters\AbstractFilter;
use Larapac\QueryFilters\Contracts\QueryFilter;
use Larapac\QueryFilters\FilterRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilterTest extends \TestCase
{
    public function test_apply_empty_filter_to_query_must_return_it_query()
    {
        $filter = $this->getFilter();
        $q = $this->getQuery();

        $this->assertEquals($q, $filter->apply(clone $q));
    }

    public function test_call_applying_from_rule()
    {
        $filter = $this->getFilter();
        $q = $this->getQuery();

        $filter->setFilter([
            new class('some', 'Bar', '') extends FilterRule
            {
                public function apply($query)
                {
                    return $query->where($this->name, $this->parameter);
                }
            }
        ]);

        $this->assertNotEquals($q, $filter->apply(clone $q));
        $this->assertNull($filter->test);
    }

    public function test_replacing_applying_rule()
    {
        $filter = $this->getFilter();
        $q = $this->getQuery();

        $filter->setFilter([
            new FilterRule('foo', 'val', ''),
            new FilterRule('foo', 'Bar', ''),
            new FilterRule('some', 'Bar', ''),
        ]);
        $filter->apply($q);

        $this->assertEquals(3, $filter->test);
   }

    public function test_array_parameter_in_rule()
    {
        $filter = $this->getFilter();
        $q = $this->getQuery();

        $filter->setFilter([
            new FilterRule('foo', ['one', 'two'], ''),
        ]);
        $filter->apply($q);

        $this->assertEquals(1, $filter->test);
    }

    /**
     * @return QueryFilter
     */
    private function getFilter()
    {
        return new class() extends AbstractFilter
        {
            public $test;

            protected function applyFoo($query, $rule)
            {
                $this->test++;
                return $query;
            }

            protected function applyFooBar($query, $rule)
            {
                $this->test += 2;
                return $query;
            }
        };
    }

    /**
     * @return Builder
     */
    private function getQuery()
    {
        $model = new class extends Model{};

        return $model->newQuery();
    }
}
