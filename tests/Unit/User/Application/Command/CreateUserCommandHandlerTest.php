<?php

namespace App\Tests\Unit\User\Application\Command;

use App\User\Application\Command\CreateUser\CreateUserCommand;
use App\User\Application\Command\CreateUser\CreateUserCommandHandler;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private CreateUserCommandHandler $getUsersQueryHandler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->getUsersQueryHandler = new CreateUserCommandHandler($this->userRepository);
    }

    /**
     * @throws Exception
     */
    public function testItCreatesAUser(): void
    {
        $user = new User(
            name: 'John Doe',
            email: 'john@example.com',
            password: 'test-password',
        );

        $this->userRepository
            ->expects($this->once())
            ->method('create');

        $query = new CreateUserCommand($user->name, $user->email, $user->password);

        $this->getUsersQueryHandler->__invoke($query);
    }
}
