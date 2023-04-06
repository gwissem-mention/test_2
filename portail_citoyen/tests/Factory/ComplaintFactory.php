<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Enum\Civility;
use App\Enum\DeclarantStatus;
use App\Form\Model\AdditionalInformation\AdditionalInformationModel;
use App\Form\Model\Address\AddressEtalabModel;
use App\Form\Model\Address\AddressForeignModel;
use App\Form\Model\Facts\FactAddressModel;
use App\Form\Model\Facts\FactsModel;
use App\Form\Model\Facts\OffenseDateModel;
use App\Form\Model\Identity\CivilStateModel;
use App\Form\Model\Identity\ContactInformationModel;
use App\Form\Model\Identity\CorporationModel;
use App\Form\Model\Identity\DeclarantStatusModel;
use App\Form\Model\Identity\IdentityModel;
use App\Form\Model\Identity\PhoneModel;
use App\Form\Model\LocationModel;
use App\Form\Model\Objects\ObjectModel;
use App\Form\Model\Objects\ObjectsModel;
use App\Session\ComplaintModel;
use Symfony\Component\Uid\Uuid;

class ComplaintFactory
{
    public function create(int $status, Uuid $id, \DateTimeInterface $createdAt, bool $frenchAddress = true): ComplaintModel
    {
        $identity = match ($status) {
            DeclarantStatus::Victim->value => $this->createIdentityVictim($frenchAddress),
            DeclarantStatus::PersonLegalRepresentative->value => $this->createIdentityPersonLegalRepresentative($frenchAddress),
            DeclarantStatus::CorporationLegalRepresentative->value => $this->createIdentityCorporationLegalRepresentative($frenchAddress),
            default => throw new \InvalidArgumentException('Invalid status'),
        };

        return (new ComplaintModel($id))
            ->setCreatedAt($createdAt)
            ->setFranceConnected(false)
            ->setAffectedService('66459')
            ->setDeclarantStatus($this->createDeclarantStatus($status))
            ->setIdentity($identity)
            ->setFacts($this->createFacts())
            ->setObjects($this->createObjects())
            ->setAdditionalInformation($this->createAdditionalInformation());
    }

    public function createDeclarantStatus(int $declarantStatus): DeclarantStatusModel
    {
        return (new DeclarantStatusModel())->setDeclarantStatus($declarantStatus);
    }

    private function createIdentityVictim(bool $frenchAddress = true): IdentityModel
    {
        return (new IdentityModel())
            ->setCivilState($this->createCivilState($frenchAddress))
            ->setContactInformation($this->createContactInformation($frenchAddress));
    }

    private function createIdentityPersonLegalRepresentative(bool $frenchAddress = true): IdentityModel
    {
        return (new IdentityModel())
            ->setCivilState($this->createCivilState($frenchAddress))
            ->setContactInformation($this->createContactInformation($frenchAddress))
            ->setRepresentedPersonCivilState($this->createCivilState($frenchAddress))
            ->setRepresentedPersonContactInformation($this->createContactInformation($frenchAddress));
    }

    private function createIdentityCorporationLegalRepresentative(bool $frenchAddress = true): IdentityModel
    {
        return (new IdentityModel())
            ->setCivilState($this->createCivilState($frenchAddress))
            ->setContactInformation($this->createContactInformation($frenchAddress))
            ->setCorporation($this->createCorporation($frenchAddress));
    }

    private function createCivilState(bool $frenchAddress = true): CivilStateModel
    {
        return (new CivilStateModel())
            ->setCivility(Civility::M->value)
            ->setFirstnames('Jean')
            ->setBirthName('Dupont')
            ->setUsageName('Paul')
            ->setBirthDate(new \DateTimeImmutable('1980-01-01'))
            ->setBirthLocation($frenchAddress ? $this->createLocationFrance() : $this->createLocationForeign())
            ->setNationality(1)
            ->setJob('1');
    }

    private function createContactInformation(bool $frenchAddress = true): ContactInformationModel
    {
        return (new ContactInformationModel())
            ->setCountry($frenchAddress ? 99100 : 99134)
            ->setEmail('jean.dupont@example.com')
            ->setFrenchAddress(true === $frenchAddress ? $this->createAddressEtalab() : null)
            ->setForeignAddress(false === $frenchAddress ? $this->createAddressForeign() : null)
            ->setPhone($this->createPhone())
            ->setMobile($this->createPhone());
    }

    private function createCorporation(bool $frenchAddress = true): CorporationModel
    {
        return (new CorporationModel())
            ->setSiren('123456789')
            ->setFrenchAddress(true === $frenchAddress ? $this->createAddressEtalab() : null)
            ->setForeignAddress(false === $frenchAddress ? $this->createAddressForeign() : null)
            ->setPhone($this->createPhone())
            ->setEmail('entreprise@gmail.com')
            ->setFunction('CEO')
            ->setName('Entreprise')
            ->setNationality('1')
            ->setCountry($frenchAddress ? 99100 : 99134);
    }

    private function createLocationFrance(): LocationModel
    {
        return (new LocationModel())
            ->setCountry(99100)
            ->setFrenchTown('75107');
    }

    private function createLocationForeign(): LocationModel
    {
        return (new LocationModel())
            ->setCountry(99134)
            ->setOtherTown('Madrid');
    }

    private function createPhone(): PhoneModel
    {
        return (new PhoneModel())
            ->setNumber('0601020304')
            ->setCountry('FR')
            ->setCode('33');
    }

    private function createAddressEtalab(): AddressEtalabModel
    {
        return (new AddressEtalabModel())
            ->setLabel('8 Boulevard du Port 80000 Amiens')
            ->setScore(0.49219200956938)
            ->setHouseNumber('8')
            ->setId('80021_6590_00008')
            ->setName('8 Boulevard du Port')
            ->setPostcode('80000')
            ->setCitycode('80021')
            ->setX(648952.58)
            ->setY(6977867.14)
            ->setCity('Amiens')
            ->setContext('80, Somme, Hauts-de-France')
            ->setType('housenumber')
            ->setImportance(0.67727)
            ->setStreet('Boulevard du Port');
    }

    private function createAddressForeign(): AddressForeignModel
    {
        return (new AddressForeignModel())
            ->setHouseNumber('134')
            ->setType('Av.')
            ->setStreet('Roque Nublo')
            ->setCity('Madrid')
            ->setPostcode('28223')
            ->setApartment('2')
            ->setContext('Pozuelo de Alarcón');
    }

    private function createFacts(): FactsModel
    {
        return (new FactsModel())
            ->setAddress((new FactAddressModel())
                ->setAddressOrRouteFactsKnown(false)
                ->setStartAddress($this->createAddressEtalab())
                ->setEndAddress($this->createAddressEtalab())
                ->setAddressAdditionalInformation('Additional information'))
            ->setPlaceNature(1)
            ->setOffenseDate((new OffenseDateModel())
                ->setExactDateKnown(false)
                ->setStartDate(new \DateTimeImmutable('2021-01-01'))
                ->setEndDate(new \DateTimeImmutable('2021-01-02'))
                ->setChoiceHour('maybe')
                ->setStartHour(new \DateTimeImmutable('2021-01-01 12:00:00'))
                ->setEndHour(new \DateTimeImmutable('2021-01-01 13:00:00')))
            ->setVictimOfViolence(true)
            ->setVictimOfViolenceText('Violence text')
            ->setDescription('Description');
    }

    private function createObjects(): ObjectsModel
    {
        return (new ObjectsModel())
            ->addObject((new ObjectModel())
                ->setCategory(1)
                ->setLabel('CI')
                ->setStatus(ObjectModel::STATUS_STOLEN)
                ->setAmount(100))
            ->addObject((new ObjectModel())
                ->setCategory(3)
                ->setLabel('iPhone')
                ->setStatus(ObjectModel::STATUS_DEGRADED)
                ->setAmount(2000)
                ->setBrand('Apple')
                ->setModel('iPhone 12')
                ->setPhoneNumberLine($this->createPhone())
                ->setOperator('Orange')
                ->setSerialNumber('1234567890'))
            ->addObject((new ObjectModel())
                ->setCategory(2)
                ->setLabel('CB')
                ->setStatus(ObjectModel::STATUS_STOLEN)
                ->setAmount(10)
                ->setBank('BNP Paribas')
                ->setBankAccountNumber('1234567890')
                ->setCreditCardNumber('4624 7482 3324 9080')
            )
            ->addObject((new ObjectModel())
                ->setCategory(4)
                ->setLabel('Voiture')
                ->setStatus(ObjectModel::STATUS_DEGRADED)
                ->setAmount(10000)
                ->setBrand('Peugeot')
                ->setModel('208')
                ->setRegistrationNumber('AB-123-CD')
                ->setRegistrationNumberCountry('FR')
                ->setInsuranceCompany('AXA')
                ->setInsuranceNumber('1234567890'));
    }

    private function createAdditionalInformation(): AdditionalInformationModel
    {
        return (new AdditionalInformationModel())
            ->setWitnesses(true)
            ->setWitnessesText('Witnesses text')
            ->setSuspectsChoice(true)
            ->setSuspectsText('Suspects text')
            ->setCctvAvailable(true)
            ->setCctvPresent(AdditionalInformationModel::CCTV_PRESENT_YES)
            ->setFsiVisit(true)
            ->setObservationMade(true);
    }
}
