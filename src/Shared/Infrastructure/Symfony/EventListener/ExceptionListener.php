<?php

namespace App\Shared\Infrastructure\Symfony\EventListener;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Exception\ResourceNotFoundException;
use App\Shared\Domain\Exception\UnauthorizedException;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Infrastructure\Exception\InfrastructureException;
use App\Shared\Infrastructure\Symfony\Exception\ExceptionResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ExceptionListener
{
    public const DEFAULT_ERROR_MESSAGE = 'error.internal_server_error';
    public const FORBIDDEN_ERROR_MESSAGE = 'exception.forbidden';

    public function __construct(private TranslatorInterface $translator)
    {
    }

    private function getStatusCode(\Throwable $exception): int
    {
        return match (true) {
            $exception instanceof ResourceNotFoundException => Response::HTTP_NOT_FOUND,
            $exception instanceof ValidationException => Response::HTTP_BAD_REQUEST,
            $exception instanceof UnauthorizedException => Response::HTTP_UNAUTHORIZED,
            $exception instanceof AccessDeniedHttpException => Response::HTTP_FORBIDDEN,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    protected function getMessage(\Throwable $throwable): string
    {
        return match (true) {
            $throwable instanceof DomainException,
            $throwable instanceof InfrastructureException => $this->translator->trans($throwable->getMessage()),
            $throwable instanceof BadRequestHttpException => $throwable->getMessage(),
            $throwable instanceof UnprocessableEntityHttpException, => $this->getMessageFromUnprocessable($throwable),
            $throwable instanceof AccessDeniedHttpException => $this->translator->trans(self::FORBIDDEN_ERROR_MESSAGE),
            default => $this->translator->trans(self::DEFAULT_ERROR_MESSAGE),
        };
    }

    protected function getMessageFromUnprocessable(UnprocessableEntityHttpException $exception): string
    {
        $missingField = explode('.', explode("\n", $exception->getPrevious()->getMessage())[0])[1];

        return $missingField . ' ' . $exception->getMessage();
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        $response = new JsonResponse();

        $message = $this->getMessage($exception);
        $statusCode = $this->getStatusCode($exception);

        $exceptionResponse = new ExceptionResponse(
            message: $message,
            errorMessage: $exception->getMessage(),
            file: $exception->getFile(),
            line: $exception->getLine()
        );

        $response->setStatusCode($statusCode);
        $response->setContent($exceptionResponse->toJsonResponse());

        $event->setResponse($response);
    }
}
