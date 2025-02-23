<?php

namespace App\Tests\Unit\User\Application\Query;

use App\User\Application\Query\GetUserProfile\GetUserProfileQuery;
use App\User\Application\Query\GetUserProfile\GetUserProfileQueryHandler;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Transformer\UserTransformer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserProfileQueryHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $userTransformer;
    private GetUserProfileQueryHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userTransformer = $this->createMock(UserTransformer::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->handler = new GetUserProfileQueryHandler($this->userRepository, $this->userTransformer);
    }

    /**
     * @throws Exception
     */
    public function testItReturnsAUserProfile(): void
    {
        $user = $this->createMock(User::class);
        $userTransformer = $this->createMock(UserTransformer::class);

        $user->name = 'John';
        $user->email = 'john@example.com';

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($user->email)
            ->willReturn($user);

        $this->userTransformer->expects($this->once())
            ->method('transform')
            ->with($user)
            ->willReturn((array) $user);

        $userTransformer->expects($this->once())
            ->method('jsonSerialize')
            ->willReturn((array) $user);

        $query = new GetUserProfileQuery($user->email);

        $result = $this->handler->__invoke($query);

        $this->assertSame($userTransformer->jsonSerialize(), $result);
    }
}
