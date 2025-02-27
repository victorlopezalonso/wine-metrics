<?php

namespace App\Tests\Unit\User\Application\Query;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Domain\Pagination\PaginatedCollection;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\User\Application\Query\ListUsers\ListUsersQuery;
use App\User\Application\Query\ListUsers\ListUsersQueryHandler;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Transformer\UserTransformer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListUsersQueryHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $userTransformer;
    private ListUsersQueryHandler $getUsersQueryHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userTransformer = $this->createMock(UserTransformer::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->getUsersQueryHandler = new ListUsersQueryHandler($this->userRepository, $this->userTransformer);
    }

    /**
     * @throws Exception
     */
    public function testItReturnsAListOfUsers(): void
    {
        $paginatedCollection = $this->createMock(PaginationInterface::class);
        $transformedCollection = $this->createMock(PaginatedCollection::class);

        $this->userRepository->expects($this->once())
            ->method('all')
            ->with(Page::default())
            ->willReturn($paginatedCollection);

        $this->userTransformer->expects($this->once())
            ->method('paginatedCollection')
            ->with($paginatedCollection)
            ->willReturn($transformedCollection);

        $query = new ListUsersQuery(Page::default());

        $result = $this->getUsersQueryHandler->__invoke($query);

        $this->assertSame($transformedCollection, $result);
    }
}
