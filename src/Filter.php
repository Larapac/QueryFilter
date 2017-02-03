<?php

namespace Larapac\QueryFilters;

use Illuminate\Http\Request;
use Larapac\QueryFilters\Contracts\RequestTranspiler;

abstract class Filter extends SimpleFilter
{
    public function __construct(Request $request, RequestTranspiler $transpiler = null)
    {
        $transpiler = $transpiler ?: new DirectQueryTranspiler();
        parent::__construct($request, $transpiler);
    }

    public function query()
    {
        $request = $this->transpiler->fromFilter($this->filter, $this->request);

        return $request->query();
    }

    public function url(array $query = [])
    {
        return $this->request->fullUrlWithQuery(array_merge($this->query(), $query));
    }

    public function isDefault()
    {
        return $this->filter == $this->defaultFilter();
    }
}
