<?php

namespace App\Tests\Unit\Sensor\Application\Query;

use App\Sensor\Application\Query\ListSensors\ListSensorsQuery;
use App\Sensor\Application\Query\ListSensors\ListSensorsQueryHandler;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use App\Sensor\Domain\Transformer\SensorTransformer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ListSensorsQueryHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @throws Exception
     */
    public function testItListsSensors(): void
    {
        $sensorRepository = $this->createMock(SensorRepositoryInterface::class);
        $sensorTransformer = $this->createMock(SensorTransformer::class);
        $listSensorsQueryHandler = new ListSensorsQueryHandler($sensorRepository, $sensorTransformer);

        $sensorRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $sensorTransformer
            ->expects($this->once())
            ->method('collection')
            ->willReturn([]);

        $query = new ListSensorsQuery();

        $listSensorsQueryHandler->__invoke($query);
    }
}
