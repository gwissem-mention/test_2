<?php

namespace App\Oodrive\Event;

use App\Oodrive\ParamsObject\SearchParamObject;

class PreSearch
{
    public const NAME = 'oodrive.search.pre_search';

    public function __construct(private readonly SearchParamObject $searchParamObject)
    {
    }

    public function getSearchParamObject(): SearchParamObject
    {
        return $this->searchParamObject;
    }
}
