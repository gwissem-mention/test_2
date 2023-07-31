<?php

declare(strict_types=1);

namespace App\Tests\Etalab;

use App\Etalab\AddressEtalabHandler;
use App\Etalab\EtalabAddressApiClient;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\EtalabInput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class AddressEtalabHandlerTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $expectedRequestData;
    /** @var array<mixed> */
    private array $expectedResponseData;

    public function setUp(): void
    {
        $this->expectedResponseData = [
            'type' => 'FeatureCollection',
            'version' => 'draft',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            2.290084,
                            49.897442,
                        ],
                    ],
                    'properties' => [
                        'label' => '8 Boulevard du Port 80000 Amiens',
                        'score' => 0.49219200956938,
                        'housenumber' => '8',
                        'id' => '80021_6590_00008',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '80000',
                        'citycode' => '80021',
                        'x' => 648952.58,
                        'y' => 6977867.14,
                        'city' => 'Amiens',
                        'context' => '80, Somme, Hauts-de-France',
                        'type' => 'housenumber',
                        'importance' => 0.67727,
                        'street' => 'Boulevard du Port',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            2.062794,
                            49.0317,
                        ],
                    ],
                    'properties' => [
                        'label' => '8 Boulevard du Port 95000 Cergy',
                        'score' => 0.4916738277512,
                        'housenumber' => '8',
                        'id' => '95127_1448_00008',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '95000',
                        'citycode' => '95127',
                        'x' => 631466.41,
                        'y' => 6881718.82,
                        'city' => 'Cergy',
                        'context' => "95, Val-d'Oise, Île-de-France",
                        'type' => 'housenumber',
                        'importance' => 0.67157,
                        'street' => 'Boulevard du Port',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            3.605884,
                            43.425225,
                        ],
                    ],
                    'properties' => [
                        'label' => '8 Boulevard du Port 34140 Mèze',
                        'score' => 0.48648473684211,
                        'housenumber' => '8',
                        'id' => '34157_0770_00008',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '34140',
                        'citycode' => '34157',
                        'x' => 749085.29,
                        'y' => 6258645.39,
                        'city' => 'Mèze',
                        'context' => '34, Hérault, Occitanie',
                        'type' => 'housenumber',
                        'importance' => 0.61449,
                        'street' => 'Boulevard du Port',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            3.036731,
                            42.79091,
                        ],
                    ],
                    'properties' => [
                        'label' => '8 Boulevard du Port 66420 Le Barcarès',
                        'score' => 0.48102564593301,
                        'housenumber' => '8',
                        'id' => '66017_0249_00008',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '66420',
                        'citycode' => '66017',
                        'x' => 703008.57,
                        'y' => 6187933.13,
                        'city' => 'Le Barcarès',
                        'context' => '66, Pyrénées-Orientales, Occitanie',
                        'type' => 'housenumber',
                        'importance' => 0.55444,
                        'street' => 'Boulevard du Port',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            -2.340983,
                            47.258811,
                        ],
                    ],
                    'properties' => [
                        'label' => '8 Boulevard du Port 44380 Pornichet',
                        'score' => 0.47683110047847,
                        'housenumber' => '8',
                        'id' => '44132_0141_00008',
                        'name' => '8 Boulevard du Port',
                        'postcode' => '44380',
                        'citycode' => '44132',
                        'x' => 296409.78,
                        'y' => 6697932.63,
                        'city' => 'Pornichet',
                        'context' => '44, Loire-Atlantique, Pays de la Loire',
                        'type' => 'housenumber',
                        'importance' => 0.5083,
                        'street' => 'Boulevard du Port',
                    ],
                ],
            ],
            'attribution' => 'BAN',
            'licence' => 'ETALAB-2.0',
            'query' => '8 bd du port',
            'limit' => 5,
        ];

        $this->expectedRequestData = ['q' => '8 bd du port', 'limit' => 5];
    }

    public function testAddressModelIsEtalab(): void
    {
        $mockResponseJson = json_encode($this->expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
        ]);

        $httpClient = new MockHttpClient($mockResponse);
        $etalabApiClient = new EtalabAddressApiClient($httpClient);
        $addressEtalabHandler = new AddressEtalabHandler($etalabApiClient);

        $etalabInput = new EtalabInput(
            '8 Boulevard du Port 80000 Amiens',
            '80021_6590_00008',
            '8 bd du port'
        );

        $responseData = $addressEtalabHandler->getAddressModel($etalabInput);

        self::assertInstanceOf(AddressEtalabModel::class, $responseData);
        self::assertSame('80021_6590_00008', $responseData->getId());
    }

    public function testAddressModelIsNotEtalab(): void
    {
        $mockResponseJson = json_encode($this->expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
        ]);

        $httpClient = new MockHttpClient($mockResponse);
        $etalabApiClient = new EtalabAddressApiClient($httpClient);
        $addressEtalabHandler = new AddressEtalabHandler($etalabApiClient);

        $etalabInput = new EtalabInput('8 Boulevard du Port 80000 Amiens', '', '');

        $responseData = $addressEtalabHandler->getAddressModel($etalabInput);

        self::assertNotInstanceOf(AddressEtalabModel::class, $responseData);
        self::assertSame('8 Boulevard du Port 80000 Amiens', $responseData->getLabel());
    }

    public function testIdIsFound(): void
    {
        $mockResponseJson = json_encode($this->expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
        ]);

        $httpClient = new MockHttpClient($mockResponse, 'https://api-adresse.data.gouv.fr');
        $etalabApiClient = new EtalabAddressApiClient($httpClient);
        $addressEtalabHandler = new AddressEtalabHandler($etalabApiClient);

        /** @var string $requestQuery */
        $requestQuery = $this->expectedRequestData['q'];
        $etalabInput = new EtalabInput($requestQuery, '80021_6590_00008', $requestQuery);

        $responseData = $addressEtalabHandler->getAddressModel($etalabInput);
        self::assertInstanceOf(AddressEtalabModel::class, $responseData);
        self::assertSame('80021_6590_00008', $responseData->getId());
    }

    public function testIdIsNotFound(): void
    {
        $mockResponseJson = json_encode($this->expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
        ]);

        $httpClient = new MockHttpClient($mockResponse);
        $etalabApiClient = new EtalabAddressApiClient($httpClient);
        $addressEtalabHandler = new AddressEtalabHandler($etalabApiClient);

        /** @var string $requestQuery */
        $requestQuery = $this->expectedRequestData['q'];
        $etalabInput = new EtalabInput($requestQuery, '80021_6590_00059', $requestQuery);

        $responseData = $addressEtalabHandler->getAddressModel($etalabInput);
        self::assertSame('8 bd du port', $responseData->getLabel());
    }
}
