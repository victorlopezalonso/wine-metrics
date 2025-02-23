<?php

namespace App\Measurement\Domain\Entity;

use App\Sensor\Domain\Entity\Sensor;
use App\Wine\Domain\Entity\Wine;

class Measurement
{
    public function __construct(
        public ?int $id = null,
        public ?Wine $wine = null,
        public ?Sensor $sensor = null,
        public ?string $value = null,
        public ?string $unit = null,
        public ?\DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {
    }
}
