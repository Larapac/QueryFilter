<?php

namespace Larapac\QueryFilters\Contracts;

use Illuminate\Http\Request;

interface RequestTranspiler
{
    /**
     * Make filter from request.
     *
     * @param Request $request
     * @return array|FilterRule[]
     */
    public function toFilter(Request $request);

    /**
     * Make request from base and data from filter.
     *
     * @param array|FilterRule[] $filter
     * @param Request $request
     * @return Request
     */
    public function fromFilter(array $filter, Request $request);
}
