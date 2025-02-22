<?php

namespace App\Shared\Domain\Exception;

class ValidationException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
