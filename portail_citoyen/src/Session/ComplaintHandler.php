<?php

declare(strict_types=1);

namespace App\Session;

use App\AppEnum\DeclarantStatus;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Objects\ObjectModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Referential\Repository\CityServiceRepository;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;

class ComplaintHandler
{
    public function __construct(
        private readonly CityServiceRepository $cityServiceRepository,
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider
    ) {
    }

    public function getAffectedService(ComplaintModel $complaint): ?string
    {
        $serviceCode = null;

        $factsStartAddress = $complaint->getFacts()?->getAddress()?->getStartAddress();
        $identityFrenchAddress = $complaint->getIdentity()?->getContactInformation()?->getFrenchAddress();
        $factsStartAddress = $factsStartAddress ?: $identityFrenchAddress;

        if ($factsStartAddress instanceof AddressEtalabModel) {
            $serviceCode = $this->cityServiceRepository->findOneBy(['cityCode' => $factsStartAddress->getCitycode()])?->getServiceCode();
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
        return $objects->getObjects()->exists(fn (int $key, ObjectModel $object) => ObjectModel::STATUS_STOLEN === $object->getStatus() && $this->objectCategoryThesaurusProvider->getChoices()['pel.object.category.registered.vehicle'] === $object->getCategory());
    }
}
