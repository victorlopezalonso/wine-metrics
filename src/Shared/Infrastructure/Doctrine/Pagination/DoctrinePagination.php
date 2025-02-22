<?php

namespace App\Shared\Infrastructure\Doctrine\Pagination;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Domain\Pagination\PaginationInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

readonly class DoctrinePagination implements PaginationInterface
{
    private Paginator $paginator;

    public function __construct(
        Query $query,
        private Page $page,
    ) {
        $this->paginator = new Paginator($query);

        $this->paginator
            ->getQuery()
            ->setFirstResult($page->offset())
            ->setMaxResults($page->results);
    }

    public function getPage(): int
    {
        return $this->page->number;
    }

    public function getPerPage(): int
    {
        return $this->page->results;
    }

    public function getTotal(): int
    {
        return count($this->paginator);
    }

    public function getPages(): int
    {
        return 0 === count($this->paginator) ? 1 : (int) (ceil(count($this->paginator) / $this->page->results));
    }

    /**
     * @throws \Exception
     */
    public function getResults(): array
    {
        return (array) $this->paginator->getIterator();
    }
}
