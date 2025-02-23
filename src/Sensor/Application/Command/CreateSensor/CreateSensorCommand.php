<?php

namespace App\Sensor\Application\Command\CreateSensor;

readonly class CreateSensorCommand
{
    public function __construct(public string $name)
    {
    }
}
