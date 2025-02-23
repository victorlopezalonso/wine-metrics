<?php

namespace App\Wine\Domain\Repository;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\Wine\Domain\Entity\Wine;

interface WineRepositoryInterface
{
    public function save(Wine $entity): void;

    public function findById(int $id): ?Wine;

    public function findByNameAndYear(string $name, int $year): ?Wine;

    public function all(Page $page, bool $withMeasurements): PaginationInterface;

    public function delete(Wine $entity): void;
}
