<?php

namespace App\Shared\Domain\Transformer;

use App\Shared\Domain\Pagination\PaginatedCollection;
use App\Shared\Domain\Pagination\PaginationInterface;

abstract class DomainAbstractTransformer implements \JsonSerializable
{
    public function transform(object $item): array
    {
        $class = static::class;
        $entityTransformer = new $class($item);

        return $entityTransformer->jsonSerialize();
    }

    public function collection(iterable $items): array
    {
        return array_map(fn ($item) => $this->transform($item), (array) $items);
    }

    public function paginatedCollection(PaginationInterface $paginatedCollection): PaginatedCollection
    {
        $items = [];

        foreach ($paginatedCollection->getResults() as $item) {
            $items[] = $this->transform($item);
        }

        return new PaginatedCollection(
            $paginatedCollection->getPage(),
            $paginatedCollection->getPerPage(),
            $paginatedCollection->getTotal(),
            $paginatedCollection->getPages(),
            $items
        );
    }
}
