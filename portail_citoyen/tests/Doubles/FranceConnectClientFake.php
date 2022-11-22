<?php

namespace App\Tests\Doubles;

use App\Security\FranceConnectAuthenticator;
use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FranceConnectClientFake extends OAuth2Client
{
    public function __construct(
        private OAuth2Client $franceConnectClient,
        private UrlGeneratorInterface $urlGenerator,
        private string $clientId,
        private string $baseUri,
    ) {
    }

    /**
     * @param string[]              $scopes
     * @param array<string, string> $options
     */
    public function redirect(array $scopes = [], array $options = []): RedirectResponse
    {
        if (0 === strlen($nonce = $options['nonce'] ?? '')) {
            throw new \LogicException('nonce is missing');
        }

        if (!array_key_exists('approval_prompt', $options) || null !== $options['approval_prompt']) {
            throw new \LogicException('approval_prompt is missing or not null');
        }

        $response = $this->franceConnectClient->redirect($scopes, $options);
        if (302 !== $response->getStatusCode()) {
            throw new \LogicException(sprintf('Wrong status code, expected %d, actual %d', 302, $response->getStatusCode()));
        }

        $location = $response->headers->get('Location', '');
        if (!str_contains($location, '?')) {
            throw new \LogicException(sprintf('Missing parameters in uri: %s', $location));
        }
        [$url, $qs] = explode('?', $location);
        $expectedUrl = rtrim($this->baseUri, '/').'/authorize';
        if ($url !== $expectedUrl) {
            throw new \LogicException(sprintf('Wrong redirect url, expected %s actual %s', $expectedUrl, $url));
        }
        parse_str($qs, $parsed);
        ksort($parsed);
        if ($parsed != [
                'client_id' => $this->clientId,
                'nonce' => $nonce,
                'redirect_uri' => $this->urlGenerator->generate(FranceConnectAuthenticator::LOGIN_CALLBACK_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL),
                'response_type' => 'code',
                'scope' => 'openid profile birth email',
                'state' => $parsed['state'] ?? 'nostate',
            ]) {
            throw new \LogicException(sprintf('Wrong parameters in %s', $qs));
        }

        $parsed['code'] = 2754;

        return new RedirectResponse($this->urlGenerator->generate(FranceConnectAuthenticator::LOGIN_CALLBACK_ROUTE, $parsed));
    }

    /**
     * @param array<string, string> $options
     */
    public function getAccessToken(array $options = [])
    {
        return $this->franceConnectClient->getAccessToken();
    }

    public function fetchUserFromToken(AccessToken $accessToken): ResourceOwnerInterface
    {
        return $this->franceConnectClient->fetchUserFromToken($accessToken);
    }
}
