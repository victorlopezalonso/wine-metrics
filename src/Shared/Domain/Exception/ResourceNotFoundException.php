<?php

namespace App\Shared\Domain\Exception;

class ResourceNotFoundException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
