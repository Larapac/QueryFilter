<?php

namespace Larapac\QueryFilters;

use Larapac\QueryFilters\Contracts\RequestTranspiler;
use Illuminate\Http\Request;

abstract class SimpleTranspiler implements RequestTranspiler
{
    /**
     * @param Request $request
     * @return array
     */
    abstract protected function extractDataFromRequest(Request $request);

    /**
     * @param array $data
     * @param Request $request
     * @return Request
     */
    abstract public function injectDataToRequest(array $data, Request $request);

    /**
     * {@inheritdoc}
     */
    public function toFilter(Request $request)
    {
        $data = $this->extractDataFromRequest($request);

        return $this->dataToFilter($data);
    }

    /**
     * {@inheritdoc}
     */
    public function fromFilter(array $filter, Request $request)
    {
        $data = $this->extractDataFromFilter($filter);

        return $this->injectDataToRequest($data, Request::createFromBase($request));
    }

    /**
     * @param array $data
     * @return array|FilterRule[]
     */
    public function dataToFilter(array $data)
    {
        $filter = [];
        foreach ($data as $key => $val) {
            $filter[] = new FilterRule($key, $val, null);
        }

        return $filter;
    }

    /**
     * @param array|FilterRule[] $filter
     * @return array
     */
    protected function extractDataFromFilter(array $filter)
    {
        $data = [];
        foreach ($filter as $rule) {
            $data[$rule->name()] = $rule->parameter();
        }

        return $data;
    }
}
