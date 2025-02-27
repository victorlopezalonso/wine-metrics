<?php

namespace App\User\Application\Query\ListUsers;

use App\Shared\Domain\Bus\HandlerInterface;
use App\Shared\Domain\Pagination\PaginatedCollection;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Transformer\UserTransformer;

readonly class ListUsersQueryHandler implements HandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserTransformer $userTransformer,
    ) {
    }

    public function __invoke(ListUsersQuery $query): PaginatedCollection
    {
        $paginatedCollection = $this->userRepository->all($query->page);

        return $this->userTransformer->paginatedCollection($paginatedCollection);
    }
}
