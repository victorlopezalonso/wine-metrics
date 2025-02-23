<?php

namespace App\User\Infrastructure\Symfony\Controller;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\User\Application\Query\ListUsers\ListUsersQuery;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
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
        responses: [
            new Response(
                response: 200,
                description: 'List of wines with measurements retrieved successfully.',
                content: [
                    new JsonContent(
                        properties: [
                            new Property(property: 'page', type: 'integer', example: 1),
                            new Property(property: 'perPage', type: 'integer', example: 10),
                            new Property(property: 'total', type: 'integer', example: 100),
                            new Property(property: 'pages', type: 'integer', example: 10),
                            new Property(property: 'results', type: 'array', items: new Items(
                                properties: [
                                    new Property(property: 'id', type: 'string', example: '12345'),
                                    new Property(property: 'name', type: 'string', example: 'John Doe'),
                                    new Property(property: 'email', type: 'string', example: 'john@example.com'),
                                    new Property(property: 'roles', type: 'array', items: new Items(type: 'string')),
                                    new Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2024-02-23 14:00:00'),
                                ],
                                type: 'object'
                            )),
                        ],
                    ),
                ]
            ),
        ]
    )]
    public function index(
        #[MapQueryParameter] ?int $page = Page::DEFAULT_NUMBER,
        #[MapQueryParameter] ?int $count = Page::DEFAULT_RESULTS_PER_PAGE,
    ): JsonResponse {
        return $this->handleWithResponse(
            new ListUsersQuery(new Page($page, $count)),
        );
    }
}
