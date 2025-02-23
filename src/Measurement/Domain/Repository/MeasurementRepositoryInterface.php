<?php

namespace App\Measurement\Domain\Repository;

use App\Measurement\Domain\Entity\Measurement;

interface MeasurementRepositoryInterface
{
    public function save(Measurement $entity): void;

    public function findById(int $id): ?Measurement;

    public function all(): array;

    public function delete(Measurement $entity): void;
}
