<?php

namespace App\Sensor\Infrastructure\Symfony\Controller;

use App\Sensor\Application\Command\CreateSensor\CreateSensorCommand;
use App\Sensor\Infrastructure\Symfony\Http\Request\CreateSensorRequest;
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

class SensorCreateController extends AbstractApiController
{
    public function __construct(
        MessageBusInterface $messageBus,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct($messageBus);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'Sensor')]
    #[Route('/v1/sensors', name: 'sensor_create', methods: ['POST'])]
    #[Post(
        summary: 'Creates a new sensor.',
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: new Model(type: CreateSensorRequest::class)
            )
        ),
    )]
    public function index(#[MapRequestPayload] CreateSensorRequest $request): JsonResponse
    {
        $this->handleMessage(
            message: new CreateSensorCommand($request->name)
        );

        return $this->json(
            data: ['message' => $this->translator->trans('message.sensor_created')],
            status: Response::HTTP_CREATED
        );
    }
}
