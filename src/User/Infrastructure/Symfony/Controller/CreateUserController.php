<?php

namespace App\User\Infrastructure\Symfony\Controller;

use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use App\User\Application\Command\CreateUser\CreateUserCommand;
use App\User\Infrastructure\Symfony\Http\Request\CreateUserRequest;
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

class CreateUserController extends AbstractApiController
{
    public function __construct(MessageBusInterface $messageBus, private readonly TranslatorInterface $translator)
    {
        parent::__construct($messageBus);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Tag(name: 'User')]
    #[Route('/v1/users', name: 'create_user', methods: ['POST'])]
    #[Post(
        summary: 'Creates a new user.',
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: new Model(type: CreateUserRequest::class)
            )
        ),
    )]
    public function index(
        #[MapRequestPayload] CreateUserRequest $request,
    ): JsonResponse {
        $this->handleMessage(
            new CreateUserCommand(
                name: $request->name,
                email: $request->email,
                password: $request->password,
            )
        );

        return $this->json(
            data: ['message' => $this->translator->trans('message.user_created')],
            status: Response::HTTP_CREATED
        );
    }
}
