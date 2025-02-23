<?php

namespace App\Tests\Unit\Sensor\Application\Query;

use App\Sensor\Application\Query\ListSensors\ListSensorsQuery;
use App\Sensor\Application\Query\ListSensors\ListSensorsQueryHandler;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Domain\Transformer\SensorTransformer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListSensorsQueryHandlerTest extends TestCase
{
    private ListSensorsQueryHandler $listSensorsQueryHandler;
    private MockObject $sensorRepository;
    private MockObject $sensorTransformer;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sensorRepository = $this->createMock(SensorRepositoryInterface::class);
        $this->sensorTransformer = $this->createMock(SensorTransformer::class);
        $this->listSensorsQueryHandler = new ListSensorsQueryHandler($this->sensorRepository, $this->sensorTransformer);
    }

    /**
     * @throws Exception
     */
    public function testItListsSensors(): void
    {
        $this->sensorRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $this->sensorTransformer
            ->expects($this->once())
            ->method('collection')
            ->willReturn([]);

        $query = new ListSensorsQuery();

        $this->listSensorsQueryHandler->__invoke($query);
    }
}
