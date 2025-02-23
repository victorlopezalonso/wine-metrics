<?php

namespace App\Measurement\Infrastructure\Symfony\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateMeasurementRequest
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    public ?int $wineId = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public ?int $sensorId = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $value = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $unit = null;
}
