<?php

namespace App\Security;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FranceConnectProvider extends AbstractProvider
{
    private const RESPONSE_OK = 200;
    private const UNAUTHORIZED = 401;

    protected ?string $baseUri = null;

    /**
     * @param array<string, mixed>  $options
     * @param array<string, object> $collaborators
     */
    public function __construct(array $options, array $collaborators)
    {
        parent::__construct($options, $collaborators);

        if (null === $this->baseUri) {
            throw new \LogicException('Missing required option baseUri');
        }

        $this->baseUri = rtrim($this->baseUri, '/');
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->baseUri.'/authorize';
    }

    /**
     * @param array<string, string> $params
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->baseUri.'/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->baseUri.'/userinfo';
    }

    /**
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return ['openid', 'profile', 'birth', 'email'];
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * @param array<string, string> $data
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (self::RESPONSE_OK === $response->getStatusCode()) {
            return;
        }

        if (self::UNAUTHORIZED === $response->getStatusCode()) {
            $authenticateHeaders = $response->getHeader('WWW-Authenticate');

            if (!is_array($data)) {
                $data = [];
            }

            $data['WWW-Authenticate header'] = $authenticateHeaders[0] ?? '';
        }

        throw new AuthenticationException(sprintf('Error status code: %d, (data: %s)', $response->getStatusCode(), json_encode($data)));
    }

    /**
     * @param array<string, string> $response
     */
    protected function createResourceOwner(array $response, AccessToken $token): GenericResourceOwner
    {
        return new GenericResourceOwner($response, 'sub');
    }

    /**
     * @param string|null $token
     *
     * @return array<string, string>
     */
    protected function getAuthorizationHeaders($token = null): array
    {
        if (null === $token) {
            throw new \RuntimeException('No token provided');
        }

        return ['Authorization' => 'Bearer '.$token];
    }
}
