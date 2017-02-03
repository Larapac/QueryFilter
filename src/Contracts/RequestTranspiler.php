<?php

namespace Larapac\QueryFilters\Contracts;

use Illuminate\Http\Request;

/**
 * Интерфейс создателя фильтра eloquent запроса на основании http запроса.
 */
interface RequestTranspiler
{
    /**
     * Создание набора правил фильтрации eloquent запроса из http запроса.
     * Make filter from request.
     *
     * @param Request $request
     * @return array|FilterRule[]
     */
    public function toFilter(Request $request);

    /**
     * Создание http запроса на основании базовго и
     * Make request from base and data from filter.
     *
     * @param array|FilterRule[] $filter
     * @param Request $request
     * @return Request
     */
    public function fromFilter(array $filter, Request $request);
}
