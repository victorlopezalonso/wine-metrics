<?php

namespace App\Wine\Infrastructure\Symfony\Controller;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\Wine\Application\Query\GetWinesWithMeasurements\GetWinesWithMeasurementsQuery;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Parameter;
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
    )]
    public function index(
        #[MapQueryParameter] ?int $page = Page::DEFAULT_NUMBER,
        #[MapQueryParameter] ?int $count = Page::DEFAULT_RESULTS_PER_PAGE,
        #[MapQueryParameter] ?bool $withMeasurements = false,
    ): JsonResponse {
        return $this->handleWithResponse(
            new GetWinesWithMeasurementsQuery(
                page: new Page($page, $count),
                withMeasurements: $withMeasurements
            )
        );
    }
}
