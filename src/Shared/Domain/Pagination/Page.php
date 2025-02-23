<?php

namespace App\Shared\Domain\Pagination;

readonly class Page
{
    public const DEFAULT_NUMBER = 1;
    public const DEFAULT_RESULTS_PER_PAGE = 10;

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
        return new self(self::DEFAULT_NUMBER, self::DEFAULT_RESULTS_PER_PAGE);
    }

    public function offset(): int
    {
        return $this->results * ($this->number - 1);
    }
}
