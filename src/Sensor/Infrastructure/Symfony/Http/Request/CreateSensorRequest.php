<?php

namespace App\Sensor\Infrastructure\Symfony\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateSensorRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $name;
}
