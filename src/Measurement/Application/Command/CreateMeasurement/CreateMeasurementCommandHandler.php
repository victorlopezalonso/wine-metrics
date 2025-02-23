<?php

namespace App\Measurement\Application\Command\CreateMeasurement;

use App\Measurement\Domain\Entity\Measurement;
use App\Measurement\Domain\Repository\MeasurementRepositoryInterface;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Exception\SensorNotFoundException;
use App\Shared\Domain\Bus\HandlerInterface;
use App\Wine\Domain\Exception\WineNotFoundException;
use App\Wine\Domain\Repository\WineRepositoryInterface;

readonly class CreateMeasurementCommandHandler implements HandlerInterface
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
        private WineRepositoryInterface $wineRepository,
        private SensorRepositoryInterface $sensorRepository,
    ) {
    }

    public function __invoke(CreateMeasurementCommand $command): void
    {
        if (!$wine = $this->wineRepository->findById($command->wineId)) {
            throw new WineNotFoundException();
        }

        if (!$sensor = $this->sensorRepository->findById($command->sensorId)) {
            throw new SensorNotFoundException();
        }

        $this->measurementRepository->save(
            new Measurement(
                wine: $wine,
                sensor: $sensor,
                value: $command->value,
                unit: $command->unit,
            )
        );
    }
}
