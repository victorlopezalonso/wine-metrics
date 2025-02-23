<?php

namespace App\User\Infrastructure\Symfony\Controller;

use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\User\Application\Query\GetUserProfile\GetUserProfileQuery;
use OpenApi\Attributes\Get;
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
    )]
    public function index(): JsonResponse
    {
        $email = $this->getUser()->getUserIdentifier();

        return $this->handleWithResponse(
            new GetUserProfileQuery($email),
        );
    }
}
