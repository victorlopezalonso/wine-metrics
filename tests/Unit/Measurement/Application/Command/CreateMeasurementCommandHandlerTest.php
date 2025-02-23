<?php

namespace App\Tests\Unit\Measurement\Application\Command;

use App\Measurement\Application\Command\CreateMeasurement\CreateMeasurementCommand;
use App\Measurement\Application\Command\CreateMeasurement\CreateMeasurementCommandHandler;
use App\Measurement\Domain\Entity\Measurement;
use App\Measurement\Domain\Repository\MeasurementRepositoryInterface;
use App\Sensor\Domain\Entity\Sensor;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Wine\Domain\Entity\Wine;
use App\Wine\Domain\Repository\WineRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CreateMeasurementCommandHandlerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItCreatesAMeasurement(): void
    {
        $measurementRepository = $this->createMock(MeasurementRepositoryInterface::class);
        $wineRepository = $this->createMock(WineRepositoryInterface::class);
        $sensorRepository = $this->createMock(SensorRepositoryInterface::class);

        $handler = new CreateMeasurementCommandHandler(
            $measurementRepository,
            $wineRepository,
            $sensorRepository
        );

        $command = new CreateMeasurementCommand(
            wineId: 1,
            sensorId: 1,
            value: '1',
            unit: 'C'
        );

        $wineRepository->expects($this->once())
            ->method('findById')
            ->with($command->wineId)
            ->willReturn(new Wine());

        $sensorRepository->expects($this->once())
            ->method('findById')
            ->with($command->sensorId)
            ->willReturn(new Sensor());

        $measurementRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Measurement::class));

        $handler->__invoke($command);
    }
}
