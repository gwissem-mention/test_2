<?php

declare(strict_types=1);

namespace App\Tests\SalesForce\Authentication;

use App\Salesforce\HttpClient\Authentication\AuthenticatedClient;
use App\Salesforce\HttpClient\Authentication\UnableToAuthenticateException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class AuthenticatedClientTest extends TestCase
{
    private const BASE_REST_URI = 'https://example.com';
    private const BASE_AUTH_URI = 'https://mctm35nfg5pprn7ql9zw-s8frr-4.auth.marketingcloudapis.com';

    public function testRequestUnauthenticated(): void
    {
        $authenticatedClient = new AuthenticatedClient(
            new MockHttpClient([
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('POST', $method);
                    $this->assertSame(sprintf('%s/v2/token', self::BASE_AUTH_URI), $url);
                    $this->assertSame('{"grant_type":"client_credentials","client_id":"clientId","client_secret":"clientSecret"}', $options['body']);

                    return new MockResponse('{"access_token": "access_token"}', [
                        'http_code' => 200,
                    ]);
                },
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('GET', $method);
                    $this->assertSame(sprintf('%s/whatever', self::BASE_REST_URI), $url);
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('normalized_headers', $options);
                    $this->assertArrayHasKey('authorization', $options['normalized_headers']);
                    $this->assertArrayHasKey(0, $options['normalized_headers']['authorization']);
                    $this->assertSame('Authorization: Bearer access_token', $options['normalized_headers']['authorization'][0]);

                    return new MockResponse('', [
                        'http_code' => 200,
                    ]);
                },
            ]),
            'clientId',
            'clientSecret',
            self::BASE_AUTH_URI,
        );

        $authenticatedClient->request('GET', '/whatever');
    }

    public function testRequestWithServerUnableToAuthenticateUs(): void
    {
        $this->expectException(UnableToAuthenticateException::class);

        $authenticatedClient = new AuthenticatedClient(
            new MockHttpClient([
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('POST', $method);
                    $this->assertSame(sprintf('%s/v2/token', self::BASE_AUTH_URI), $url);
                    $this->assertSame('{"grant_type":"client_credentials","client_id":"clientId","client_secret":"clientSecret"}', $options['body']);

                    return new MockResponse('', [
                        'http_code' => 401,
                    ]);
                },
            ]),
            'clientId',
            'clientSecret',
            self::BASE_AUTH_URI,
        );

        $authenticatedClient->request('GET', '/whatever');
    }

    public function testRequestWithExpiredToken(): void
    {
        $authenticatedClient = new AuthenticatedClient(
            new MockHttpClient([
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('POST', $method);
                    $this->assertSame(sprintf('%s/v2/token', self::BASE_AUTH_URI), $url);
                    $this->assertSame('{"grant_type":"client_credentials","client_id":"clientId","client_secret":"clientSecret"}', $options['body']);

                    return new MockResponse('{"access_token":"expired_access_token"}', [
                        'http_code' => 200,
                    ]);
                },
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('GET', $method);
                    $this->assertSame(sprintf('%s/whatever', self::BASE_REST_URI), $url);
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('normalized_headers', $options);
                    $this->assertArrayHasKey('authorization', $options['normalized_headers']);
                    $this->assertArrayHasKey(0, $options['normalized_headers']['authorization']);
                    $this->assertSame('Authorization: Bearer expired_access_token', $options['normalized_headers']['authorization'][0]);

                    return new MockResponse('', [
                        'http_code' => 401,
                    ]);
                },
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('POST', $method);
                    $this->assertSame(sprintf('%s/v2/token', self::BASE_AUTH_URI), $url);
                    $this->assertSame('{"grant_type":"client_credentials","client_id":"clientId","client_secret":"clientSecret"}', $options['body']);

                    return new MockResponse('{"access_token":"new_access_token"}', [
                        'http_code' => 200,
                    ]);
                },
                function ($method, $url, $options): MockResponse {
                    $this->assertSame('GET', $method);
                    $this->assertSame(sprintf('%s/whatever', self::BASE_REST_URI), $url);
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('normalized_headers', $options);
                    $this->assertArrayHasKey('authorization', $options['normalized_headers']);
                    $this->assertArrayHasKey(0, $options['normalized_headers']['authorization']);
                    $this->assertSame('Authorization: Bearer new_access_token', $options['normalized_headers']['authorization'][0]);

                    return new MockResponse('', [
                        'http_code' => 200,
                    ]);
                },
            ]),
            'clientId',
            'clientSecret',
            self::BASE_AUTH_URI
        );

        $authenticatedClient->request('GET', '/whatever');
    }
}
