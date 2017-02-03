<?php

namespace Larapac\QueryFilters;

use Larapac\QueryFilters\Contracts\RequestTranspiler;
use Illuminate\Http\Request;

class NullTranspiler implements RequestTranspiler
{
    /**
     * {@inheritdoc}
     */
    public function toFilter(Request $request)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function fromFilter(array $filter, Request $request)
    {
        return $request;
    }
}
