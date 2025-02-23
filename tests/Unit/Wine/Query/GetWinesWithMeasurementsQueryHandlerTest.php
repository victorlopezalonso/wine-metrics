<?php

namespace App\Tests\Unit\Sensor\Application\Query;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Domain\Pagination\PaginatedCollection;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\Wine\Application\Query\ListWines\ListWinesQuery;
use App\Wine\Application\Query\ListWines\ListWinesQueryHandler;
use App\Wine\Domain\Repository\WineRepositoryInterface;
use App\Wine\Domain\Transformer\WineTransformer;
use App\Wine\Domain\Transformer\WineWithMeasurementsTransformer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetWinesWithMeasurementsQueryHandlerTest extends TestCase
{
    protected MockObject $wineRepository;
    protected MockObject $wineTransformer;
    protected MockObject $wineWithMeasurementsTransformer;
    protected MockObject $paginationInterface;
    protected MockObject $paginatedCollection;

    protected ListWinesQueryHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->wineRepository = $this->createMock(WineRepositoryInterface::class);

        $this->wineTransformer = $this->createMock(WineTransformer::class);
        $this->wineWithMeasurementsTransformer = $this->createMock(WineWithMeasurementsTransformer::class);

        $this->paginationInterface = $this->createMock(PaginationInterface::class);
        $this->paginatedCollection = $this->createMock(PaginatedCollection::class);

        $this->handler = new ListWinesQueryHandler(
            $this->wineRepository,
            $this->wineTransformer,
            $this->wineWithMeasurementsTransformer,
        );
    }

    /**
     * @throws Exception
     */
    public function testItListsWinesWithoutMeasurements(): void
    {
        $this->wineRepository->expects($this->once())
            ->method('all')
            ->willReturn($this->paginationInterface);

        $this->wineTransformer->expects($this->once())
            ->method('paginatedCollection')
            ->willReturn($this->paginatedCollection);

        $this->wineWithMeasurementsTransformer->expects($this->never())
            ->method('paginatedCollection');

        $listWinesWithoutMeasurement = new ListWinesQuery(
            page: Page::default(),
            withMeasurements: false
        );

        $this->handler->__invoke($listWinesWithoutMeasurement);
    }

    /**
     * @throws Exception
     */
    public function testItListsWinesWithMeasurements(): void
    {
        $this->wineRepository->expects($this->once())
            ->method('all')
            ->willReturn($this->paginationInterface);

        $this->wineWithMeasurementsTransformer->expects($this->once())
            ->method('paginatedCollection')
            ->willReturn($this->paginatedCollection);

        $this->wineTransformer->expects($this->never())
            ->method('paginatedCollection');

        $listWinesWithMeasurement = new ListWinesQuery(
            page: Page::default(),
            withMeasurements: true
        );

        $this->handler->__invoke($listWinesWithMeasurement);
    }
}
