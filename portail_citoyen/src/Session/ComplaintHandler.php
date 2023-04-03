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

        $placeNatureChoices = $this->naturePlaceThesaurusProvider->getChoices();
        $factsStartAddress = $complaint->getFacts()?->getAddress()?->getStartAddress();
        $identityFrenchAddress = $complaint->getIdentity()?->getContactInformation()?->getFrenchAddress();
        $factsAddress = match ($complaint->getFacts()?->getPlaceNature()) {
            $placeNatureChoices['pel.nature.place.home'] => $identityFrenchAddress,
            default => $factsStartAddress instanceof AddressEtalabModel ? $factsStartAddress : $identityFrenchAddress,
        };

        if ($factsAddress instanceof AddressEtalabModel) {
            $serviceCode = $this->cityServiceRepository->findOneBy(['cityCode' => $factsAddress->getCitycode()])?->getServiceCode();
        }

        return $serviceCode;
    }
}
