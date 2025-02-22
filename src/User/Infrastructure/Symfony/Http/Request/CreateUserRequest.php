<?php

namespace App\User\Infrastructure\Symfony\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 3, max: 255)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    public string $password;
}
