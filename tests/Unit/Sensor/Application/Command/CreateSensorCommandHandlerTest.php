<?php

namespace App\Tests\Unit\Sensor\Application\Command;

use App\Sensor\Application\Command\CreateSensor\CreateSensorCommand;
use App\Sensor\Application\Command\CreateSensor\CreateSensorCommandHandler;
use App\Sensor\Domain\Entity\Sensor;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Exception\SensorAlreadyExistsException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateSensorCommandHandlerTest extends TestCase
{
    private MockObject $sensorRepository;
    private CreateSensorCommandHandler $createSensorCommandHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sensorRepository = $this->createMock(SensorRepositoryInterface::class);
        $this->createSensorCommandHandler = new CreateSensorCommandHandler($this->sensorRepository);
    }

    /**
     * @throws Exception
     */
    public function testItCreatesASensor(): void
    {
        $sensor = new Sensor(name: 'test-sensor-name');

        $this->sensorRepository
            ->method('findByName')
            ->with($sensor->name)
            ->willReturn(null);

        $this->sensorRepository
            ->expects($this->once())
            ->method('save');

        $command = new CreateSensorCommand($sensor->name);

        $this->createSensorCommandHandler->__invoke($command);
    }

    /**
     * @throws Exception
     */
    public function testFailsToCreatesASensorWithTheSameName(): void
    {
        $sensor = new Sensor(name: 'test-sensor-name');

        $this->sensorRepository
            ->expects($this->never())
            ->method('save');

        $this->sensorRepository
            ->method('findByName')
            ->with($sensor->name)
            ->willReturn($sensor);

        $this->expectException(SensorAlreadyExistsException::class);

        $command = new CreateSensorCommand($sensor->name);

        $this->createSensorCommandHandler->__invoke($command);
    }
}
