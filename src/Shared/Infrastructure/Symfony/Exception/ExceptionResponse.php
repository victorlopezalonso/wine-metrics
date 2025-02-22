<?php

namespace App\Shared\Infrastructure\Symfony\Exception;

readonly class ExceptionResponse implements \JsonSerializable
{
    public function __construct(
        private string $message,
        private string $errorMessage,
        private string $file,
        private int $line,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'error' => [
                'message' => $this->errorMessage,
                'trace' => 'Error in file ' . $this->file . ' at line ' . $this->line,
            ],
        ];
    }

    public function toJsonResponse(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}
