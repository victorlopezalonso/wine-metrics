<?php

namespace App\Wine\Domain\Entity;

class Wine
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?int $year = null,
    ) {
    }
}
