<?php

namespace App\Sensor\Infrastructure\Symfony\Controller;

use App\Sensor\Application\Query\ListSensors\ListSensorsQuery;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SensorListController extends AbstractApiController
{
    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'Sensor')]
    #[Route('/v1/sensors', name: 'sensor_list', methods: ['GET'])]
    #[Get(
        summary: 'Returns a list of sensors.',
        responses: [
            new Response(
                response: 200,
                description: 'List of sensors retrieved successfully',
                content: [
                    new JsonContent(
                        type: 'array',
                        items: new Items(
                            properties: [
                                new Property(property: 'id', type: 'integer', example: 1),
                                new Property(property: 'name', type: 'string', example: 'Sensor 1'),
                            ],
                            type: 'object'
                        )
                    ),
                ]
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->handleWithResponse(
            message: new ListSensorsQuery()
        );
    }
}
