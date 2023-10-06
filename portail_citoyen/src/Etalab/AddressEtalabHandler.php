<?php

declare(strict_types=1);

namespace App\Etalab;

use App\Form\Factory\AddressModelFactory;
use App\Form\Model\Address\AbstractSerializableAddress;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\EtalabInput;

class AddressEtalabHandler
{
    public function __construct(
        private readonly EtalabApiClientInterface $etalabAddressApiClient,
    ) {
    }

    public function getAddressModel(EtalabInput $etalabInput): AbstractSerializableAddress
    {
        if ('' === $etalabInput->getAddressId() || '' === $etalabInput->getAddressSearchSaved()) {
            return AddressModelFactory::create($etalabInput->getAddressSearch())
                ->setLatitude($etalabInput->getLatitude())
                ->setLongitude($etalabInput->getLongitude());
        }
        try {
            /** @var array<array<array<mixed>>> $datalabResponse */
            $datalabResponse = $this->etalabAddressApiClient->search($etalabInput->getAddressSearchSaved(), 5);
        } catch (\Exception) {
            return AddressModelFactory::create($etalabInput->getAddressSearch(), $etalabInput->getLatitude(), $etalabInput->getLongitude());
        }

        $addressEtalabModel = $this->findOneById($etalabInput->getAddressId(), $datalabResponse['features']);

        if ($etalabInput->getLatitude() && $etalabInput->getLongitude()) {
            $addressEtalabModel?->setLatitude($etalabInput->getLatitude())->setLongitude($etalabInput->getLongitude());
        }

        return $addressEtalabModel ?? AddressModelFactory::create($etalabInput->getAddressSearch(), $etalabInput->getLatitude(), $etalabInput->getLongitude());
    }

    /**
     * @param array<array<mixed>> $addresses
     */
    private function findOneById(string $id, array $addresses = []): ?AddressEtalabModel
    {
        foreach ($addresses as $address) {
            /** @var array<string, string|float|int|null> $properties */
            $properties = $address['properties'];
            /** @var array<string, string|array<int, float|null>> $geometries */
            $geometries = $address['geometry'];
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
                    strval($geometries['coordinates'][1]) ?: null,
                    strval($geometries['coordinates'][0]) ?: null,
                );
            }
        }

        return null;
    }
}
