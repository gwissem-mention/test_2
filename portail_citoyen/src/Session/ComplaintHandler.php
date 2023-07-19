<?php

declare(strict_types=1);

namespace App\Session;

use App\AppEnum\DeclarantStatus;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Objects\ObjectModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Referential\Repository\CityServiceRepository;
use App\Thesaurus\NaturePlaceThesaurusProviderInterface;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;

class ComplaintHandler
{
    public function __construct(
        private readonly NaturePlaceThesaurusProviderInterface $naturePlaceThesaurusProvider,
        private readonly CityServiceRepository $cityServiceRepository,
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider
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

    public function isAppointmentRequired(ComplaintModel $complaint): bool
    {
        return !$complaint->isFranceConnected()
            || in_array($complaint->getIdentity()?->getDeclarantStatus(), [
                DeclarantStatus::CorporationLegalRepresentative->value,
                /* Person Legal Representative must be hidden for the experimentation */
//                DeclarantStatus::PersonLegalRepresentative->value
            ], true)
            || $complaint->getFacts()?->isVictimOfViolence()
            || (null !== $complaint->getObjects() && $this->hasObjectsAppointmentRequired($complaint->getObjects()));
    }

    private function hasObjectsAppointmentRequired(ObjectsModel $objects): bool
    {
        return $objects->getObjects()->exists(function (int $key, ObjectModel $object) {
            return ObjectModel::STATUS_STOLEN === $object->getStatus() && $this->objectCategoryThesaurusProvider->getChoices()['pel.object.category.registered.vehicle'] === $object->getCategory();
        });
    }
}
