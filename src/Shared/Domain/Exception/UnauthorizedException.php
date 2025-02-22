<?php

namespace App\Shared\Domain\Exception;

class UnauthorizedException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
