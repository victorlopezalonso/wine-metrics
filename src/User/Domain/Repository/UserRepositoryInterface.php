<?php

namespace App\User\Domain\Repository;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Domain\Pagination\PaginationInterface;
use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function create(User $user): void;

    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function all(Page $page): PaginationInterface;

    public function delete(User $user): void;
}
