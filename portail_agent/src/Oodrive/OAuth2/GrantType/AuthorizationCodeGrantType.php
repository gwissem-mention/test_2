<?php

declare(strict_types=1);

namespace App\Oodrive\OAuth2\GrantType;

use App\Oodrive\Event\PostRefreshAccessToken;
use App\Oodrive\Event\PostRequestAccessToken;
use App\Oodrive\Event\PreRefreshAccessToken;
use App\Oodrive\Event\PreRequestAccessToken;
use App\Oodrive\OAuth2\Exception\OAuthException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AuthorizationCodeGrantType implements GrantTypeInterface
{
    public function __construct(
        private readonly HttpClientInterface $oodriveClient,
        private readonly LoggerInterface $oodriveLogger,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly string $tokenUrl,
        private readonly string $workspace,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $username,
        private readonly string $password,
        private readonly ?string $refreshToken = null,
    ) {
    }

    public function getTokens(): Tokens
    {
        if (!$this->refreshToken) {
            $response = $this->getNewAccessToken();
        } else {
            $response = $this->getRefreshedAccessToken();
        }

        return $this->extractTokens($response);
    }

    public function getRefreshTokenGrant(string $refreshToken): GrantTypeInterface
    {
        return new AuthorizationCodeGrantType(
            $this->oodriveClient,
            $this->oodriveLogger,
            $this->eventDispatcher,
            $this->tokenUrl,
            $this->workspace,
            $this->clientId,
            $this->clientSecret,
            $this->username,
            $this->password,
            $refreshToken
        );
    }

    private function getNewAccessToken(): ResponseInterface
    {
        $this->eventDispatcher->dispatch(new PreRequestAccessToken('password', $this->workspace), PreRequestAccessToken::NAME);
        $this->oodriveLogger->info(sprintf('Requesting access token in name of application %s on behalf of user %s', $this->clientId, $this->username));

        $response = $this->oodriveClient->request('POST', $this->tokenUrl, [
            'headers' => [
                'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret))),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'password',
                'workspace' => $this->workspace,
                'username' => $this->username,
                'password' => $this->password,
            ],
        ]);

        $this->eventDispatcher->dispatch(new PostRequestAccessToken(), PostRequestAccessToken::NAME);
        $this->oodriveLogger->info(sprintf('Successfully requested access token in name of application %s on behalf of user %s', $this->clientId, $this->username));

        return $response;
    }

    private function getRefreshedAccessToken(): ResponseInterface
    {
        if (!$this->refreshToken) {
            throw new OAuthException('Error while refreshing access token, no refresh token found.');
        }

        $this->eventDispatcher->dispatch(new PreRefreshAccessToken($this->workspace), PreRefreshAccessToken::NAME);
        $this->oodriveLogger->info(sprintf('Refreshing access token in name of application %s on behalf of user %s', $this->clientId, $this->username));

        $response = $this->oodriveClient->request('POST', $this->tokenUrl, [
            'headers' => [
                'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret))),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'refresh_token',
                'workspace' => $this->workspace,
                'refresh_token' => $this->refreshToken,
            ],
        ]);

        $this->eventDispatcher->dispatch(new PostRefreshAccessToken(), PostRefreshAccessToken::NAME);
        $this->oodriveLogger->info(sprintf('Successuly refreshed access token in name of application %s on behalf of user %s', $this->clientId, $this->username));

        return $response;
    }

    private function extractTokens(ResponseInterface $response): Tokens
    {
        try {
            $responseBody = $response->getContent();
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new OAuthException('Error when calling token endpoint. Error was : '.$e->getMessage(), 0, $e);
        }

        try {
            $token = json_decode($responseBody, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new OAuthException('Error when parsing token endpoint JSON response. JSON error was: '.$e->getMessage(), 0, $e);
        }

        if (null === $token && 'null' !== $responseBody) {
            throw new OAuthException('Error when parsing token endpoint JSON response.');
        }

        if (!is_array($token) || !array_key_exists('access_token', $token) || !is_string($token['access_token'])) {
            throw new OAuthException('Access token not found in token endpoint response.');
        }

        if (!array_key_exists('refresh_token', $token) || !is_string($token['refresh_token'])) {
            throw new OAuthException('Refresh token not found in token endpoint response.');
        }

        return new Tokens(
            $token['access_token'],
            $token['refresh_token']
        );
    }
}
