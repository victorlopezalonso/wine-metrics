<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\ValidationException;

class UserAlreadyExistsException extends ValidationException
{
    public function __construct()
    {
        parent::__construct('exception.user_already_exists');
    }
}
