<?php

namespace App\User\Domain\Transformer;

use App\Shared\Domain\Transformer\AbstractTransformer;
use App\User\Domain\Entity\User;

class UserTransformer extends AbstractTransformer
{
    public function __construct(private readonly User $user)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'roles' => $this->user->roles,
            'createdAt' => $this->user->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
