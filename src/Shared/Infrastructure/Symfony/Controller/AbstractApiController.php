<?php

namespace App\Shared\Infrastructure\Symfony\Controller;

use App\Shared\Domain\Bus\AsyncInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class AbstractApiController extends AbstractController
{
    use HandleTrait;

    public const DEFAULT_HEADERS = ['Content-Type' => 'application/json'];

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws ExceptionInterface
     */
    public function handleWithResponse(object $message, int $response = Response::HTTP_OK, array $headers = self::DEFAULT_HEADERS): JsonResponse
    {
        $result = $this->handleMessage($message);

        return $this->json($result, $response, $headers);
    }

    /**
     * @throws ExceptionInterface
     */
    public function handleMessage(object $message): mixed
    {
        if ($message instanceof AsyncInterface) {
            $this->messageBus->dispatch($message);

            return [
                'message' => 'Message dispatched successfully',
            ];
        }

        return $this->handle($message);
    }
}
