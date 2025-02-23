<?php

namespace App\User\Application\Query\GetUserProfile;

readonly class GetUserProfileQuery
{
    public function __construct(public string $email)
    {
    }
}
