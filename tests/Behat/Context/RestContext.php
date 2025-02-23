<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Shared\Infrastructure\Symfony\Security\SecurityUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class RestContext implements Context
{
    private array $headers = [
        'ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json',
    ];

    private RefreshTokenInterface $refreshToken;

    private const LOG_HTTP_CODE_RESPONSES = [500];

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly RefreshTokenManagerInterface $refreshTokenManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private ?Response $response,
        private readonly UserRepositoryInterface $userRepository,
    ) {
        $this->kernel->boot();
    }

    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs(string $username): void
    {
        if (!$user = $this->userRepository->findByEmail($username)) {
            throw new \RuntimeException('User not found');
        }

        $jwt = $this->jwtManager->create(new SecurityUser($user));

        $this->headers = array_merge($this->headers, ['AUTHORIZATION' => 'Bearer ' . $jwt]);
    }

    private function createRequest(string $method, string $path, ?PyStringNode $body = null): Request
    {
        $request = Request::create($path, $method, [], [], [], [], $body?->getRaw());

        $request->headers->add($this->headers);

        return $request;
    }

    private function logResponse(): void
    {
        if (in_array($this->response->getStatusCode(), self::LOG_HTTP_CODE_RESPONSES)) {
            echo $this->response->getContent();
        }
    }

    /**
     * @Given I have a valid refresh token for user :user
     */
    public function iHaveAValidRefreshTokenForUser(string $user): void
    {
        $user = $this->userRepository->findByEmail($user);

        $this->refreshToken = $this->refreshTokenGenerator->createForUserWithTtl(new SecurityUser($user), 3600);

        $this->refreshTokenManager->save($this->refreshToken);
    }

    /**
     * @Given I have an expired refresh token for user :user
     */
    public function iHaveAnExpiredRefreshTokenForUser(string $user): void
    {
        $user = $this->userRepository->findByEmail($user);

        $this->refreshToken = $this->refreshTokenGenerator->createForUserWithTtl(new SecurityUser($user), 3600);

        $this->refreshTokenManager->save($this->refreshToken);

        $this->refreshToken->setValid(new \DateTimeImmutable('-1 day'));
    }

    /**
     * @When I send a :method request to :path
     *
     * @throws \Exception
     */
    public function iSendARequestTo(string $method, string $path): void
    {
        $request = $this->createRequest($method, $path);

        $this->response = $this->kernel->handle($request);

        $this->logResponse();
    }

    /**
     * @When I send a :method request to :path with body:
     *
     * @throws \Exception
     */
    public function iSendARequestToWithBody(string $method, string $path, PyStringNode $body): void
    {
        if (isset($this->refreshToken) && str_contains($body->getRaw(), 'valid_refresh_token')) {
            $body = new PyStringNode([
                str_replace(
                    'valid_refresh_token',
                    $this->refreshToken->getRefreshToken(),
                    $body->getRaw()
                ),
            ], $body->getLine());
        }

        $request = $this->createRequest($method, $path, $body);

        $this->response = $this->kernel->handle($request);

        $this->logResponse();
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }

        if ($this->response->getStatusCode() !== $statusCode) {
            throw new \RuntimeException(sprintf('Expected status code %d, got %d', $statusCode, $this->response->getStatusCode()));
        }
    }

    /**
     * @Then the JSON node :name should exist
     *
     * @throws \Exception
     */
    public function theJsonNodeShouldExist(string $name): void
    {
        $data = json_decode($this->response->getContent(), true);

        if (!isset($data[$name])) {
            throw new \Exception("Response does not contain key '$name'");
        }
    }

    /**
     * @Then the response should contain :text
     */
    public function theResponseShouldContain(string $text): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }

        if (false === str_contains($this->response->getContent(), $text)) {
            throw new \RuntimeException(sprintf('Response does not contain text "%s"', $text));
        }
    }

    /**
     * @Then the response should not contain :text
     */
    public function theResponseShouldNotContain(string $text): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }

        if (true === str_contains($this->response->getContent(), $text)) {
            throw new \RuntimeException(sprintf('Response contains text "%s"', $text));
        }
    }
}
