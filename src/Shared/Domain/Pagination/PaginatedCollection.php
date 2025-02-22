<?php

namespace App\Shared\Domain\Pagination;

readonly class PaginatedCollection implements \JsonSerializable
{
    public function __construct(
        private int $page,
        private int $perPage,
        private int $total,
        private int $pages,
        private array $results,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'page' => $this->page,
            'perPage' => $this->perPage,
            'total' => $this->total,
            'pages' => $this->pages,
            'results' => $this->results,
        ];
    }
}
