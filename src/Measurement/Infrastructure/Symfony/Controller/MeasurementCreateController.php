<?php

namespace App\Measurement\Infrastructure\Symfony\Controller;

use App\Measurement\Application\Command\CreateMeasurement\CreateMeasurementCommand;
use App\Measurement\Infrastructure\Symfony\Http\Request\CreateMeasurementRequest;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MeasurementCreateController extends AbstractApiController
{
    public function __construct(MessageBusInterface $messageBus, private readonly TranslatorInterface $translator)
    {
        parent::__construct($messageBus);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'Measurement')]
    #[Route('/v1/measurements', name: 'measurement_create', methods: ['POST'])]
    #[Post(
        summary: 'Creates a new user.',
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: new Model(type: CreateMeasurementRequest::class)
            )
        ),
    )]
    public function index(#[MapRequestPayload] CreateMeasurementRequest $request): JsonResponse
    {
        $this->handleMessage(
            new CreateMeasurementCommand(
                wineId: $request->wineId,
                sensorId: $request->sensorId,
                value: $request->value,
                unit: $request->unit,
            )
        );

        return $this->json(
            data: ['message' => $this->translator->trans('message.measurement_created')],
            status: Response::HTTP_CREATED
        );
    }
}
