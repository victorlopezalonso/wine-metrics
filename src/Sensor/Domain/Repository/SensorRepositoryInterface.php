<?php

namespace App\Sensor\Domain\Repository;

use App\Sensor\Domain\Entity\Sensor;

interface SensorRepositoryInterface
{
    public function save(Sensor $entity): void;

    public function findById(int $id): ?Sensor;

    public function findByName(string $name): ?Sensor;

    public function all(): array;

    public function delete(Sensor $entity): void;
}
