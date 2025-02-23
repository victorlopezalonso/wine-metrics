<?php

namespace App\Sensor\Domain\Transformer;

use App\Sensor\Domain\Entity\Sensor;
use App\Shared\Domain\Transformer\AbstractTransformer;

class SensorTransformer extends AbstractTransformer
{
    public function __construct(private readonly Sensor $sensor)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->sensor->id,
            'name' => $this->sensor->name,
        ];
    }
}
