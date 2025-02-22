<?php

namespace App\User\Application\Query\GetUsers;

use App\Shared\Domain\Pagination\Page;

readonly class GetUsersQuery
{
    public function __construct(public Page $page)
    {
    }
}
