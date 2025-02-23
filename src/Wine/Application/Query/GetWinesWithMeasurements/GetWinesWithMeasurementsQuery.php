<?php

namespace App\Wine\Application\Query\GetWinesWithMeasurements;

use App\Shared\Domain\Pagination\Page;

readonly class GetWinesWithMeasurementsQuery
{
    public function __construct(public Page $page, public bool $withMeasurements = false)
    {
    }
}
