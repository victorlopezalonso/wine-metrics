<?php

namespace App\Wine\Domain\Transformer;

use App\Measurement\Domain\Transformer\MeasurementTransformer;

class WineWithMeasurementsTransformer extends WineTransformer
{
    private function getTransformedMeasurements(): array
    {
        if (!is_iterable($this->wine->measurements)) {
            return [];
        }

        $measurements = [];

        foreach ($this->wine->measurements as $measurement) {
            $measurements[] = new MeasurementTransformer($measurement);
        }

        return $measurements;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->wine->id,
            'name' => $this->wine->name,
            'year' => $this->wine->year,
            'measurements' => $this->getTransformedMeasurements(),
        ];
    }
}
