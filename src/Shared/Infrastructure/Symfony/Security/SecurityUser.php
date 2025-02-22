<?php

namespace App\Shared\Infrastructure\Symfony\Security;

use App\User\Domain\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(public User $user)
    {
    }

    public function getRoles(): array
    {
        return $this->user->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->email;
    }

    public function getPassword(): ?string
    {
        return $this->user->password;
    }
}
