<?php

namespace App\Wine\Application\Query\ListWines;

use App\Shared\Domain\Bus\HandlerInterface;
use App\Shared\Domain\Pagination\PaginatedCollection;
use App\Wine\Domain\Repository\WineRepositoryInterface;
use App\Wine\Domain\Transformer\WineTransformer;
use App\Wine\Domain\Transformer\WineWithMeasurementsTransformer;

readonly class ListWinesQueryHandler implements HandlerInterface
{
    public function __construct(
        private WineRepositoryInterface $wineRepository,
        private WineTransformer $wineTransformer,
        private WineWithMeasurementsTransformer $wineWithMeasurementsTransformer,
    ) {
    }

    public function __invoke(ListWinesQuery $query): PaginatedCollection
    {
        $wines = $this->wineRepository->all($query->page, $query->withMeasurements);

        return $query->withMeasurements
            ? $this->wineWithMeasurementsTransformer->paginatedCollection($wines)
            : $this->wineTransformer->paginatedCollection($wines);
    }
}
