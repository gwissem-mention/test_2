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
            return AddressModelFactory::create($etalabInput->getAddressSearch());
        }
        try {
            /** @var array<array<array<mixed>>> $datalabResponse */
            $datalabResponse = $this->etalabAddressApiClient->search($etalabInput->getAddressSearchSaved(), 5);
        } catch (\Exception) {
            return AddressModelFactory::create($etalabInput->getAddressSearch());
        }

        return $this->findOneById(
            $etalabInput->getAddressId(),
            $datalabResponse['features']
        ) ?? AddressModelFactory::create($etalabInput->getAddressSearch());
    }

    /**
     * @param array<array<mixed>> $addresses
     */
    private function findOneById(string $id, array $addresses = []): ?AddressEtalabModel
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
}
