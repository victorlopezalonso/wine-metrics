<?php

namespace App\Measurement\Domain\Transformer;

use App\Measurement\Domain\Entity\Measurement;
use App\Shared\Domain\Transformer\AbstractTransformer;

class MeasurementTransformer extends AbstractTransformer
{
    public function __construct(private readonly Measurement $measurement)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->measurement->value,
            'unit' => $this->measurement->unit,
            'date' => $this->measurement->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
