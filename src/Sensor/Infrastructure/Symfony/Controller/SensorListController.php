<?php

namespace App\Sensor\Infrastructure\Symfony\Controller;

use App\Sensor\Application\Query\ListSensors\ListSensorsQuery;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use OpenApi\Attributes\Get;
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
    )]
    public function index(): JsonResponse
    {
        return $this->handleWithResponse(
            message: new ListSensorsQuery()
        );
    }
}
