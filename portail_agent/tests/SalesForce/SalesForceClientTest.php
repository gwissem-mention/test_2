<?php

declare(strict_types=1);

namespace App\Tests\SalesForce;

use App\Salesforce\HttpClient\ApiDataFormat\SalesForceApiDataInterface;
use App\Salesforce\HttpClient\Authentication\AuthenticatedClient;
use App\Salesforce\HttpClient\SalesForceApiEventDefinition;
use App\Salesforce\HttpClient\SalesForceClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\SerializerInterface;

class SalesForceClientTest extends KernelTestCase
{
    private const BASE_REST_URI = 'https://mctm35nfg5pprn7ql9zw-s8frr-4.rest.marketingcloudapis.com';
    private const BASE_AUTH_URI = 'https://mctm35nfg5pprn7ql9zw-s8frr-4.auth.marketingcloudapis.com';

    public function testSendEvent(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $eventDefinitionData = new class(complaintDeclarationNumber: 'PEL_2023_12345678', identityFirstName: 'John', identityLastName: 'Doe', unitName: null) implements SalesForceApiDataInterface {
            public function __construct(
                #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
                #[SerializedName('identity_firstName')] public string $identityFirstName,
                #[SerializedName('identity_lastName')] public string $identityLastName,
                #[SerializedName('unit_name')] public ?string $unitName,
            ) {
            }
        };

        $eventDefinition = new class('aEventDefinitionKey', 'aContactKey', $eventDefinitionData) extends SalesForceApiEventDefinition {
        };

        $salesForceClient = new SalesForceClient(
            /* @phpstan-ignore-next-line */
            $container->get(SerializerInterface::class),
            new AuthenticatedClient(
                new MockHttpClient([
                    function ($method, $url, $options): MockResponse {
                        $this->assertSame('POST', $method);
                        $this->assertSame(sprintf('%s/v2/token', self::BASE_AUTH_URI), $url);
                        $this->assertSame(
                            '{"grant_type":"client_credentials","client_id":"salesForceClientId","client_secret":"salesForceClientSecret","account_id":"accountId"}',
                            $options['body']
                        );

                        return new MockResponse('{"access_token":"access_token"}', [
                            'http_code' => 200,
                        ]);
                    },
                    function ($method, $url, $options): MockResponse {
                        $this->assertSame('POST', $method);
                        $this->assertSame(sprintf('%s/interaction/v1/events', self::BASE_REST_URI), $url);
                        $this->assertArrayHasKey('headers', $options);
                        $this->assertArrayHasKey('normalized_headers', $options);
                        $this->assertArrayHasKey('authorization', $options['normalized_headers']);
                        $this->assertArrayHasKey(0, $options['normalized_headers']['authorization']);
                        $this->assertSame('Authorization: Bearer access_token', $options['normalized_headers']['authorization'][0]);
                        $this->assertSame(
                            '{"EventDefinitionKey":"aEventDefinitionKey","ContactKey":"aContactKey","Data":{"complaint_declarationNumber":"PEL_2023_12345678","identity_firstName":"John","identity_lastName":"Doe"}}',
                            $options['body']
                        );

                        return new MockResponse('', [
                            'http_code' => 200,
                        ]);
                    },
                ], self::BASE_REST_URI),
                'salesForceClientId',
                'salesForceClientSecret',
                self::BASE_AUTH_URI,
                'accountId',
            ),
            self::BASE_REST_URI,
        );

        $salesForceClient->sendEvent($eventDefinition);
    }
}
