<?php

namespace Larapac\QueryFilters;

use Illuminate\Http\Request;

class DirectQueryTranspiler extends SimpleTranspiler
{
    /**
     * @param Request $request
     * @return array
     */
    protected function extractDataFromRequest(Request $request)
    {
        return $request->input();
    }

    /**
     * @param array $data
     * @param Request $request
     * @return Request
     */
    public function injectDataToRequest(array $data, Request $request)
    {
        $request->replace($data);

        return $request;
    }
}
