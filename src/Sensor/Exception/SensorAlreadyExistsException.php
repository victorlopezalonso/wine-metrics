<?php

namespace App\Sensor\Exception;

use App\Shared\Domain\Exception\ValidationException;

class SensorAlreadyExistsException extends ValidationException
{
    public function __construct()
    {
        parent::__construct('exception.sensor_already_exists');
    }
}
