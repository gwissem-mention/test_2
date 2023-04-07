<?php

declare(strict_types=1);

namespace App\Oodrive\OAuth2;

use App\Oodrive\OAuth2\Cache\TokensCache;
use App\Oodrive\OAuth2\Cache\TokensCacheInterface;
use App\Oodrive\OAuth2\GrantType\GrantTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class OAuth2Client implements OAuth2ClientInterface
{
    private const MAX_CACHE_RETRIES = 2;

    private TokensCacheInterface $cache;

    public function __construct(
        private readonly HttpClientInterface $oodriveClient,
        private readonly GrantTypeInterface $grant,
    ) {
        $this->cache = new TokensCache();
    }

    public function setCache(TokensCacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param array<string, int> $options
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $grant = $this->grant;

        for ($tries = 0; $tries < self::MAX_CACHE_RETRIES; ++$tries) {
            $tokens = $this->cache->get($grant);
            $this->addBearer($options, $tokens->getAccessToken());
            $response = $this->oodriveClient->request($method, $url, $options);

            if (!$this->hasAuthenticationFailed($response)) {
                return $response;
            }

            $this->cache->clear();

            if (($refreshToken = $tokens->getRefreshToken()) !== null) {
                $grant = $grant->getRefreshTokenGrant($refreshToken);
            }
        }

        throw new \RuntimeException();
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->oodriveClient->stream($responses, $timeout);
    }

    /**
     * @param array<string, int> $options
     *
     * @return $this
     */
    public function withOptions(array $options): static
    {
        return new self($this->oodriveClient->withOptions($options), $this->grant);
    }

    /**
     * @param array<string, int> $options
     */
    private function addBearer(array &$options, string $token): void
    {
        if (!array_key_exists('headers', $options)) {
            $options['headers'] = [];
        }

        if (!is_array($options['headers'])) {
            return;
        }

        $options['headers']['Authorization'] = sprintf('Bearer %s', $token);
    }

    private function hasAuthenticationFailed(ResponseInterface $response): bool
    {
        return Response::HTTP_UNAUTHORIZED === $response->getStatusCode();
    }
}
