<?php

namespace App\Wine\Application\Query\ListWines;

use App\Shared\Domain\Pagination\Page;

readonly class ListWinesQuery
{
    public function __construct(public Page $page, public bool $withMeasurements = false)
    {
    }
}
