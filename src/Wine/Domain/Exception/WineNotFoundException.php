<?php

namespace App\Wine\Domain\Exception;

use App\Shared\Domain\Exception\ResourceNotFoundException;

class WineNotFoundException extends ResourceNotFoundException
{
    public function __construct()
    {
        parent::__construct('exception.wine_not_found');
    }
}
