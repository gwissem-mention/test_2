<?php

declare(strict_types=1);

namespace App\Session;

use App\AppEnum\DeclarantStatus;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Objects\ObjectModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Referential\Entity\NaturePlace;
use App\Referential\Repository\CityServiceRepository;
use App\Referential\Repository\NaturePlaceRepository;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;

class ComplaintHandler
{
    private const HOME_NATURE_PLACE = 'Domicile, logement et dépendances';

    public function __construct(
        private readonly NaturePlaceRepository $naturePlaceRepository,
        private readonly CityServiceRepository $cityServiceRepository,
        private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider
    ) {
    }

    public function getAffectedService(ComplaintModel $complaint): ?string
    {
        $serviceCode = $naturePlace = null;

        $factsStartAddress = $complaint->getFacts()?->getAddress()?->getStartAddress();
        $identityFrenchAddress = $complaint->getIdentity()?->getContactInformation()?->getFrenchAddress();

        if (null !== $complaint->getFacts()?->getPlaceNature()) {
            /** @var NaturePlace $naturePlace */
            $naturePlace = $this->naturePlaceRepository->find($complaint->getFacts()->getPlaceNature());
        }

        $factsAddress = match ($naturePlace?->getLabel()) {
            self::HOME_NATURE_PLACE => $identityFrenchAddress,
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
        return $objects->getObjects()->exists(fn (int $key, ObjectModel $object) => ObjectModel::STATUS_STOLEN === $object->getStatus() && $this->objectCategoryThesaurusProvider->getChoices()['pel.object.category.registered.vehicle'] === $object->getCategory());
    }
}
