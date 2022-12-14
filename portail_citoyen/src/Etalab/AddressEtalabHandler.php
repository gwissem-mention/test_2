<?php

declare(strict_types=1);

namespace App\Etalab;

use App\Form\Factory\AddressModelFactory;
use App\Form\Model\AddressEtalabModel;
use App\Form\Model\AddressModel;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressEtalabHandler
{
    private const SEARCH_LIMIT = 5;
    private const BASE_URI = 'https://api-adresse.data.gouv.fr';

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    /**
     * @param array<array<mixed>> $addresses
     */
    public function findOneById(string $id, array $addresses = []): ?AddressEtalabModel
    {
        foreach ($addresses as $address) {
            /** @var array<string, mixed> $properties */
            $properties = $address['properties'];
            if (!empty($properties) && $id === $properties['id']) {
                return AddressModelFactory::createFromEtalab(
                    strval($properties['label']) ?: null,
                    strval($properties['id']) ?: null,
                    strval($properties['type']) ?: null,
                    floatval($properties['score']) ?: null,
                    strval($properties['housenumber'] ?? '') ?: null,
                    strval($properties['street'] ?? '') ?: null,
                    strval($properties['name']) ?: null,
                    strval($properties['postcode']) ?: null,
                    strval($properties['citycode']) ?: null,
                    strval($properties['city']) ?: null,
                    strval($properties['district'] ?? '') ?: null,
                    strval($properties['context']) ?: null,
                    floatval($properties['x']) ?: null,
                    floatval($properties['y']) ?: null,
                    floatval($properties['importance']) ?: null,
                );
            }
        }

        return null;
    }

    /**
     * @return array<mixed>
     *
     * @throws ExceptionInterface
     */
    public function getSearchFeatures(string $query, int $limit = self::SEARCH_LIMIT): array
    {
        /** @var array<array<mixed>> $features */
        $features = $this->search($query, $limit)['features'] ?? [];

        return $features;
    }

    public function getAddressModel(string $label, string $query, string $selectionId): AddressModel
    {
        if ('' === $selectionId || '' === $query) {
            return AddressModelFactory::create($label);
        }
        try {
            /** @var array<array<mixed>> $datalabResponse */
            $datalabResponse = $this->getSearchFeatures($query);
        } catch (ExceptionInterface) {
            return AddressModelFactory::create(
                $label
            );
        }

        return $this->findOneById(
            $selectionId,
            $datalabResponse
        ) ?? AddressModelFactory::create($label);
    }

    /**
     * @return array<mixed>
     *
     * @throws ExceptionInterface
     */
    private function search(string $query, int $limit = self::SEARCH_LIMIT): array
    {
        $response = $this->client->request('GET', self::BASE_URI.'/search/', [
            'query' => [
                'q' => $query,
                'limit' => $limit,
            ],
        ]);

        return $response->toArray();
    }
}
