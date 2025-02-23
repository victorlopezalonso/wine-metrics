<?php

namespace App\User\Infrastructure\Symfony\Controller;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\User\Application\Query\ListUsers\ListUsersQuery;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserListController extends AbstractApiController
{
    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'User')]
    #[Route('/v1/users', name: 'user_list', methods: ['GET'])]
    #[Get(
        summary: 'Returns a list of users.',
        parameters: [
            new Parameter(name: 'page', in: 'query', required: false, schema: new Schema(type: 'integer')),
            new Parameter(name: 'count', in: 'query', required: false, schema: new Schema(type: 'integer')),
        ],
    )]
    public function index(
        #[MapQueryParameter] ?int $page = 1,
        #[MapQueryParameter] ?int $count = 10,
    ): JsonResponse {
        return $this->handleWithResponse(
            new ListUsersQuery(new Page($page, $count)),
        );
    }
}
