<?php

namespace App\Tests\Unit\Measurement\Application\Command;

use App\Measurement\Application\Command\CreateMeasurement\CreateMeasurementCommand;
use App\Measurement\Application\Command\CreateMeasurement\CreateMeasurementCommandHandler;
use App\Measurement\Domain\Entity\Measurement;
use App\Measurement\Domain\Repository\MeasurementRepositoryInterface;
use App\Sensor\Domain\Entity\Sensor;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Exception\SensorNotFoundException;
use App\Wine\Domain\Entity\Wine;
use App\Wine\Domain\Exception\WineNotFoundException;
use App\Wine\Domain\Repository\WineRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateMeasurementCommandHandlerTest extends TestCase
{
    private CreateMeasurementCommandHandler $handler;
    private MockObject $measurementRepository;
    private MockObject $wineRepository;
    private MockObject $sensorRepository;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->measurementRepository = $this->createMock(MeasurementRepositoryInterface::class);
        $this->wineRepository = $this->createMock(WineRepositoryInterface::class);
        $this->sensorRepository = $this->createMock(SensorRepositoryInterface::class);

        $this->handler = new CreateMeasurementCommandHandler(
            $this->measurementRepository,
            $this->wineRepository,
            $this->sensorRepository
        );
    }

    /**
     * @throws Exception
     */
    public function testItCreatesAMeasurementWithValidData(): void
    {
        $command = new CreateMeasurementCommand(
            wineId: 1,
            sensorId: 1,
            value: '1',
            unit: 'C'
        );

        $this->wineRepository->expects($this->once())
            ->method('findById')
            ->with($command->wineId)
            ->willReturn(new Wine());

        $this->sensorRepository->expects($this->once())
            ->method('findById')
            ->with($command->sensorId)
            ->willReturn(new Sensor());

        $this->measurementRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Measurement::class));

        $this->handler->__invoke($command);
    }

    /**
     * @throws Exception
     */
    public function testItFailsToCreateAMeasurementWithInvalidWineId(): void
    {
        $command = new CreateMeasurementCommand(
            wineId: 1,
            sensorId: 1,
            value: '1',
            unit: 'C'
        );

        $this->wineRepository->expects($this->once())
            ->method('findById')
            ->with($command->wineId)
            ->willReturn(null);

        $this->sensorRepository->expects($this->never())
            ->method('findById');

        $this->measurementRepository->expects($this->never())
            ->method('save');

        $this->expectException(WineNotFoundException::class);

        $this->handler->__invoke($command);
    }

    /**
     * @throws Exception
     */
    public function testItFailsToCreateAMeasurementWithInvalidSensorId(): void
    {
        $command = new CreateMeasurementCommand(
            wineId: 1,
            sensorId: 1,
            value: '1',
            unit: 'C'
        );

        $this->wineRepository->expects($this->once())
            ->method('findById')
            ->with($command->wineId)
            ->willReturn(new Wine());

        $this->sensorRepository->expects($this->once())
            ->method('findById')
            ->with($command->sensorId)
            ->willReturn(null);

        $this->measurementRepository->expects($this->never())
            ->method('save');

        $this->expectException(SensorNotFoundException::class);

        $this->handler->__invoke($command);
    }
}
