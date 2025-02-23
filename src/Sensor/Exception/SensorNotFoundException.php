<?php

namespace App\Sensor\Exception;

use App\Shared\Domain\Exception\ResourceNotFoundException;

class SensorNotFoundException extends ResourceNotFoundException
{
    public function __construct()
    {
        parent::__construct('exception.sensor_not_found');
    }
}
