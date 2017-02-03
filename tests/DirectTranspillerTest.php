<?php

use Larapac\QueryFilters\DirectQueryTranspiler;
use Illuminate\Http\Request;

class DirectTranspillerTest extends \TestCase
{
    public function test_from_empty_request_empty_filter()
    {
        $transpiler = new DirectQueryTranspiler();

        $this->assertEquals([], $transpiler->toFilter($this->getRequest()));
    }

    public function test_from_empty_filter_empty_data_array()
    {
        $transpiler = new DirectQueryTranspiler();
        $request = $this->getRequest();

        $this->assertEquals($request, $transpiler->fromFilter([], $request));
    }

    public function test_from_to_equals()
    {
        $transpiler = new DirectQueryTranspiler();
        $request = $this->getRequest();
        $request->offsetSet('foo', 'bar');

        $this->assertEquals($request, $transpiler->fromFilter($transpiler->toFilter($request), $request));
    }

    /**
     * @return Request
     */
    private function getRequest()
    {
        return $this->app['request'];
    }
}
