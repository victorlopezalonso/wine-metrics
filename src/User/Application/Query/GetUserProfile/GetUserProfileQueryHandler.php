<?php

namespace App\User\Application\Query\GetUserProfile;

use App\Shared\Domain\Bus\HandlerInterface;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Transformer\UserTransformer;

readonly class GetUserProfileQueryHandler implements HandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserTransformer $userTransformer,
    ) {
    }

    public function __invoke(GetUserProfileQuery $query): array
    {
        if (!$user = $this->userRepository->findByEmail($query->email)) {
            throw new UserNotFoundException();
        }

        return $this->userTransformer->transform($user);
    }
}
