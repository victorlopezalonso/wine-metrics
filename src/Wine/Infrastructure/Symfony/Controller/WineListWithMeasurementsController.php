<?php

namespace App\Wine\Infrastructure\Symfony\Controller;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\Wine\Application\Query\ListWines\ListWinesQuery;
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

class WineListWithMeasurementsController extends AbstractApiController
{
    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'Wine')]
    #[Route('/v1/wines', name: 'wines_list_with_measurements', methods: ['GET'])]
    #[Get(
        summary: 'Returns the list of wines with measurements.',
        parameters: [
            new Parameter(name: 'page', in: 'query', required: false, schema: new Schema(type: 'integer')),
            new Parameter(name: 'count', in: 'query', required: false, schema: new Schema(type: 'integer')),
            new Parameter(name: 'withMeasurements', in: 'query', required: false, schema: new Schema(type: 'boolean')),
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
                                    new Property(property: 'id', type: 'integer', example: 1),
                                    new Property(property: 'name', type: 'string', example: 'Wine 1'),
                                    new Property(property: 'year', type: 'string', example: '2021'),
                                    new Property(property: 'measurements', type: 'array', items: new Items(
                                        properties: [
                                            new Property(property: 'value', type: 'string', example: '12.5'),
                                            new Property(property: 'unit', type: 'string', example: 'ºC'),
                                            new Property(property: 'date', type: 'string', format: 'date-time', example: '2024-02-23 14:00:00'),
                                        ],
                                        type: 'object'
                                    )),
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
        #[MapQueryParameter] ?bool $withMeasurements = false,
    ): JsonResponse {
        return $this->handleWithResponse(
            new ListWinesQuery(
                page: new Page($page, $count),
                withMeasurements: $withMeasurements
            )
        );
    }
}
