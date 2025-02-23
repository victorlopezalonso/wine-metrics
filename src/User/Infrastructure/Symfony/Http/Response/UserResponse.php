<?php

namespace App\User\Infrastructure\Symfony\Http\Response;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'UserResponse',
    title: 'User Response',
    properties: [
        new Property(property: 'id', type: 'string', example: '12345'),
        new Property(property: 'name', type: 'string', example: 'John Doe'),
        new Property(property: 'email', type: 'string', example: 'john@example.com'),
        new Property(property: 'roles', type: 'array', items: new Items(type: 'string')),
        new Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2024-02-23 14:00:00'),
    ],
    type: 'object'
)]
class UserResponse
{
}
