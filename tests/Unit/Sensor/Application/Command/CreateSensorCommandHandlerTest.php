<?php

namespace App\Tests\Unit\Sensor\Application\Command;

use App\Sensor\Application\Command\CreateSensor\CreateSensorCommand;
use App\Sensor\Application\Command\CreateSensor\CreateSensorCommandHandler;
use App\Sensor\Domain\Entity\Sensor;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Exception\SensorAlreadyExistsException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CreateSensorCommandHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @throws Exception
     */
    public function testItCreatesASensor(): void
    {
        $sensorRepository = $this->createMock(SensorRepositoryInterface::class);
        $createSensorCommandHandler = new CreateSensorCommandHandler($sensorRepository);
        $sensor = $this->createMock(Sensor::class);

        $sensor->name = 'test-sensor-name';

        $sensorRepository
            ->method('findByName')
            ->with($sensor->name)
            ->willReturn(null);

        $sensorRepository
            ->expects($this->once())
            ->method('save');

        $command = new CreateSensorCommand($sensor->name);

        $createSensorCommandHandler->__invoke($command);
    }

    /**
     * @throws Exception
     */
    public function testFailsToCreatesASensorWithTheSameName(): void
    {
        $sensorRepository = $this->createMock(SensorRepositoryInterface::class);
        $createSensorCommandHandler = new CreateSensorCommandHandler($sensorRepository);
        $sensor = $this->createMock(Sensor::class);

        $sensor->name = 'existing-sensor-name';

        $sensorRepository
            ->expects($this->never())
            ->method('save');

        $sensorRepository
            ->method('findByName')
            ->with($sensor->name)
            ->willReturn($sensor);

        $this->expectException(SensorAlreadyExistsException::class);

        $command = new CreateSensorCommand($sensor->name);

        $createSensorCommandHandler->__invoke($command);
    }
}
