<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\Authentication;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class AuthenticatedClient implements AuthenticatedClientInterface
{
    private ?string $accessToken = null;

    public function __construct(
        private readonly HttpClientInterface $salesforceClient,
        private readonly string $salesForceClientId,
        private readonly string $salesForceClientSecret,
        private readonly string $salesForceAuthDomain,
    ) {
    }

    /**
     * @param array<string, int> $options
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        if (null === $this->accessToken) {
            $this->accessToken = $this->authenticate();
        }

        $this->addBearer($options, $this->accessToken);
        $response = $this->salesforceClient->request($method, $url, $options);

        if (Response::HTTP_UNAUTHORIZED === $response->getStatusCode()) {
            $this->accessToken = $this->authenticate();
            $this->addBearer($options, $this->accessToken);
            $response = $this->salesforceClient->request($method, $url, $options);
        }

        return $response;
    }

    /**
     * @param array<string, int> $options
     */
    private function addBearer(array &$options, string $accessToken): void
    {
        if (!array_key_exists('headers', $options)) {
            $options['headers'] = [];
        }

        if (!is_array($options['headers'])) {
            return;
        }

        $options['headers']['Authorization'] = sprintf('Bearer %s', $accessToken);
    }

    public function stream(iterable|ResponseInterface $responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->salesforceClient->stream($responses, $timeout);
    }

    /**
     * @param array<string, int> $options
     */
    public function withOptions(array $options): static
    {
        return new self(
            $this->salesforceClient->withOptions($options),
            $this->salesForceClientId,
            $this->salesForceClientSecret,
            $this->salesForceAuthDomain,
        );
    }

    private function authenticate(): string
    {
        $response = $this->salesforceClient->request(
            'POST',
            sprintf('%s%s', $this->salesForceAuthDomain, '/v2/token'),
            [
                'json' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->salesForceClientId,
                    'client_secret' => $this->salesForceClientSecret,
                ],
            ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new UnableToAuthenticateException();
        }

        return $response->toArray()['access_token'];
    }
}
