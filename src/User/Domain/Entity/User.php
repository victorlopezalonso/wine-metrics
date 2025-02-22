<?php

namespace App\User\Domain\Entity;

class User
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?array $roles = ['ROLE_USER'],
        public ?bool $active = true,
        public ?\DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        public ?\DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {
    }
}
