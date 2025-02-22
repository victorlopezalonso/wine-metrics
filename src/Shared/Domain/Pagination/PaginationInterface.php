<?php

namespace App\Shared\Domain\Pagination;

interface PaginationInterface
{
    public function getPage(): int;

    public function getPerPage(): int;

    public function getTotal(): int;

    public function getPages(): int;

    public function getResults(): array;
}
