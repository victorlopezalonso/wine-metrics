<?php

namespace App\User\Infrastructure\Symfony\Controller;

use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\User\Application\Query\GetUserProfile\GetUserProfileQuery;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserGetMeController extends AbstractApiController
{
    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'User')]
    #[Route('/v1/users/me', name: 'user_get_me', methods: ['GET'])]
    #[Get(
        summary: 'Returns the authenticated user profile.',
        responses: [
            new Response(
                response: 200,
                description: 'User profile retrieved successfully',
                content: [
                    new JsonContent(
                        properties: [
                            new Property(property: 'id', type: 'string', example: '12345'),
                            new Property(property: 'name', type: 'string', example: 'John Doe'),
                            new Property(property: 'email', type: 'string', example: 'john@example.com'),
                            new Property(property: 'roles', type: 'array', items: new Items(type: 'string')),
                            new Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2024-02-23 14:00:00'),
                        ]
                    ),
                ]
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $email = $this->getUser()->getUserIdentifier();

        return $this->handleWithResponse(
            new GetUserProfileQuery($email),
        );
    }
}
