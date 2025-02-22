<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\ResourceNotFoundException;

class UserNotFoundException extends ResourceNotFoundException
{
    public function __construct()
    {
        parent::__construct('exception.user_not_found');
    }
}
