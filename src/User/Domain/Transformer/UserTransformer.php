<?php

namespace App\User\Domain\Transformer;

use App\Shared\Domain\Transformer\DomainAbstractTransformer;
use App\User\Domain\Entity\User;

class UserTransformer extends DomainAbstractTransformer
{
    public function __construct(private readonly User $user)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'createdAt' => $this->user->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
