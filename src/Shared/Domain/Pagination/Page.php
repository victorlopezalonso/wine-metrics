<?php

namespace App\Shared\Domain\Pagination;

readonly class Page
{
    public function __construct(public int $number, public int $results)
    {
        $this->asserts();
    }

    private function asserts(): void
    {
        assert($this->number > 0);
        assert($this->results > 0);
    }

    public static function default(): self
    {
        return new self(1, 10);
    }

    public function offset(): int
    {
        return $this->results * ($this->number - 1);
    }
}
