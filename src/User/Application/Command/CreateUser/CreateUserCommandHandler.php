<?php

namespace App\User\Application\Command\CreateUser;

use App\Shared\Domain\Bus\HandlerInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;

readonly class CreateUserCommandHandler implements HandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $this->userRepository->create(
            new User(
                name: $command->name,
                email: $command->email,
                password: $command->password
            )
        );
    }
}
