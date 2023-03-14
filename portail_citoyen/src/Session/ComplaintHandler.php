<?php

declare(strict_types=1);

namespace App\Session;

use App\Form\Model\Address\AddressEtalabModel;
use App\Referential\Repository\CityServiceRepository;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;

class ComplaintHandler
{
    public function __construct(
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
        private readonly CityServiceRepository $cityServiceRepository,
    ) {
    }

    public function getAffectedService(ComplaintModel $complaint): ?string
    {
        $serviceCode = null;
        // First case implementation, facts are at home, take the identity address to get the affected service
        if ($this->naturePlaceThesaurusProvider->getChoices()['pel.nature.place.home'] === $complaint->getFacts()?->getPlaceNature()) {
            $identityFrenchAddress = $complaint->getIdentity()?->getContactInformation()?->getFrenchAddress();
            if ($identityFrenchAddress instanceof AddressEtalabModel) {
                $serviceCode = $this->cityServiceRepository->findOneBy(['cityCode' => $identityFrenchAddress->getCitycode()])?->getServiceCode();
            }
        }

        return $serviceCode;
    }
}
