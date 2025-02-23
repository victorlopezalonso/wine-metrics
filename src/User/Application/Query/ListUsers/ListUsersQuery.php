<?php

namespace App\User\Application\Query\ListUsers;

use App\Shared\Domain\Pagination\Page;

readonly class ListUsersQuery
{
    public function __construct(public Page $page)
    {
    }
}
