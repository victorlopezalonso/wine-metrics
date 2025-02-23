<?php

namespace App\Wine\Domain\Transformer;

use App\Shared\Domain\Transformer\AbstractTransformer;
use App\Wine\Domain\Entity\Wine;

class WineTransformer extends AbstractTransformer
{
    public function __construct(protected readonly Wine $wine)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->wine->id,
            'name' => $this->wine->name,
            'year' => $this->wine->year,
        ];
    }
}
