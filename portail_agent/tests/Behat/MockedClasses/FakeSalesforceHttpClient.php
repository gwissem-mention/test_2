<?php

declare(strict_types=1);

namespace App\Tests\Behat\MockedClasses;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class FakeSalesforceHttpClient implements HttpClientInterface
{
    private const BASE_REST_URI = 'https://mctm35nfg5pprn7ql9zw-s8frr-4.rest.marketingcloudapis.com';
    private const BASE_AUTH_URI = 'https://mctm35nfg5pprn7ql9zw-s8frr-4.auth.marketingcloudapis.com';

    /** @var MockResponse[] */
    private array $responses;

    public function __construct()
    {
        $this->responses = [
            sprintf('%s/v2/token', self::BASE_AUTH_URI) => new MockResponse('{"access_token":"access_token"}', [
                'http_code' => 200,
            ]),
            sprintf('%s/interaction/v1/events', self::BASE_REST_URI) => new MockResponse('', [
                'http_code' => 201,
            ]),
        ];
    }

    /**
     * @param array<string, string> $options
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $response = $this->responses[$url] ?? null;

        if (null === $response) {
            throw new \LogicException(sprintf('There is no response for url: %s', $url));
        }

        return (new MockHttpClient($response, 'https://salesforce.fake'))->request($method, $url);
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        throw new \LogicException(sprintf('%s() is not implemented', __METHOD__));
    }

    /**
     * @param array<string, string> $options
     */
    public function withOptions(array $options): static
    {
        return $this;
    }
}
