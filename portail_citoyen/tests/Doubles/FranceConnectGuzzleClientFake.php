<?php

namespace App\Tests\Doubles;

use App\Enum\Gender;
use App\Security\FranceConnectAuthenticator;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FranceConnectGuzzleClientFake implements ClientInterface
{
    private const RESPONSE_OK = 200;
    private const UNAUTHORIZED = 401;

    public static bool $accessTokenServerError = false;
    public static bool $userInfoServerError = false;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private string $clientId,
        private string $clientSecret,
        private string $baseUri,
    ) {
    }

    /**
     * @param array<string, string> $options
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $actualBaseUri = sprintf(
            '%s://%s/api/v1', $request->getUri()->getScheme(),
            $request->getUri()->getHost(),
        );

        $expectedBaseUri = rtrim($this->baseUri, '/');
        if ($expectedBaseUri !== $actualBaseUri) {
            throw new \LogicException(sprintf('Wrong base uri (expected %s, actual %s))', $expectedBaseUri, $actualBaseUri));
        }

        return match ($path = $request->getUri()->getPath()) {
            '/api/v1/token' => $this->tokenCall($request, $options),
            '/api/v1/userinfo' => $this->userInfoCall($request, $options),
            default => throw new \LogicException(sprintf('Unknew path %s', $path)),
        };
    }

    /**
     * @param array<string, string> $options
     */
    private function tokenCall(RequestInterface $request, array $options = []): ResponseInterface
    {
        if ('POST' !== $request->getMethod()) {
            throw new \LogicException(sprintf('Wrong request method (expected: POST, actual: %s', $request->getMethod()));
        }

        $contentTypeHeaders = $request->getHeader('Content-Type');
        if ('application/x-www-form-urlencoded' !== ($contentTypeHeaders[0] ?? '')) {
            throw new \LogicException(sprintf('Wrong Content-Type (actual: %s, expected: application/x-www-form-urlencoded)', $contentTypeHeaders[0] ?? ''));
        }

        parse_str((string) $request->getBody(), $actualParams);
        ksort($actualParams);
        $expectedParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => '2754',
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->urlGenerator->generate(FranceConnectAuthenticator::LOGIN_CALLBACK_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        if ($actualParams !== $expectedParams) {
            throw new \LogicException(sprintf('Wrong body received (expected: %s, actual: %s)', http_build_query($expectedParams), http_build_query($actualParams)));
        }

        if (self::$accessTokenServerError) {
            self::$accessTokenServerError = false;

            return new Response(400, ['Content-Type' => 'application/json'], json_encode([
                'error' => 'invalid_request',
            ]));
        }

        return new Response(
            self::RESPONSE_OK,
            ['Content-Type' => 'application/json'],
            json_encode(['access_token' => 'my-access-token', 'id_token' => 'my-id-token']),
        );
    }

    /**
     * @param array<string, string> $options
     */
    private function userInfoCall(RequestInterface $request, array $options = []): ResponseInterface
    {
        if ('GET' !== $request->getMethod()) {
            throw new \LogicException(sprintf('Wrong request method (expected: GET, actual: %s', $request->getMethod()));
        }

        $authorizationHeaders = $request->getHeader('Authorization');
        if ('Bearer my-access-token' !== ($authorizationHeaders[0] ?? '')) {
            throw new \LogicException(sprintf('Wrong authorization header (expected: Bearer my-access-token, actual %s)', $authorizationHeaders[0] ?? ''));
        }

        if (self::$userInfoServerError) {
            self::$userInfoServerError = false;

            return new Response(self::UNAUTHORIZED, [
                'WWW-Authenticate' => 'error="invalid_token", error_description="The Access Token expired"',
            ]);
        }

        return new Response(self::RESPONSE_OK, [], json_encode([
            'sub' => 'michel-dupont-id',
            'family_name' => 'DUPONT',
            'given_name' => 'Michel',
            'preferred_username' => '',
            'birthdate' => '1967-03-02',
            'birthplace' => '75107',
            'birthcountry' => '99100',
            'gender' => Gender::MALE->value,
            'email' => 'michel.dupont@example.com',
        ]));
    }

    /**
     * @param array<string, string> $options
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        throw new \BadMethodCallException('sendAsync should not be called');
    }

    /**
     * @param array<string, string> $options
     */
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        throw new \BadMethodCallException('sendAsync should not be called');
    }

    /**
     * @param array<string, string> $options
     */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        throw new \BadMethodCallException('sendAsync should not be called');
    }

    public function getConfig(?string $option = null)
    {
        throw new \BadMethodCallException('sendAsync should not be called');
    }
}
