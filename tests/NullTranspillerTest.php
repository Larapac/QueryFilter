<?php

use Larapac\QueryFilters\FilterRule;
use Larapac\QueryFilters\NullTranspiler;

class NullTranspillerTest extends \TestCase
{
    public function test_make_filter_returns_empty_array()
    {
        $transpiler = new NullTranspiler();

        $request = $this->app['request'];
        $this->assertEquals([], $transpiler->toFilter($request));

        $request->offsetSet('foo', 'bar');
        $this->assertEquals([], $transpiler->toFilter($request));
    }

    public function test_request_from_filter_not_changed()
    {
        $transpiler = new NullTranspiler();
        $request = $this->app['request'];

        $this->assertEquals($request, $transpiler->fromFilter([], $request));

        $filter = [new FilterRule('foo', 'bar', '')];
        $this->assertEquals($request, $transpiler->fromFilter($filter, $request));
    }
}
