<?php

namespace App\Measurement\Application\Command\CreateMeasurement;

readonly class CreateMeasurementCommand
{
    public function __construct(
        public int $wineId,
        public int $sensorId,
        public string $value,
        public string $unit,
    ) {
    }
}
