<?php

namespace App\Sensor\Application\Query\ListSensors;

use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Domain\Transformer\SensorTransformer;
use App\Shared\Domain\Bus\HandlerInterface;

readonly class ListSensorsQueryHandler implements HandlerInterface
{
    public function __construct(
        private SensorRepositoryInterface $sensorRepository,
        private SensorTransformer $sensorTransformer,
    ) {
    }

    public function __invoke(ListSensorsQuery $query): array
    {
        $sensors = $this->sensorRepository->all();

        return $this->sensorTransformer->collection($sensors);
    }
}
