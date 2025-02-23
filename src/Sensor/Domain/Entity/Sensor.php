<?php

namespace App\Sensor\Domain\Entity;

class Sensor
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
    ) {
    }
}
