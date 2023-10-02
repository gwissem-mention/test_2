<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Corporation;
use App\Entity\Identity;

class CorporationRepresentedDTO
{
    private string $siretNumber;
    private string $sirenNumber;
    private string $nicNumber;
    private string $companyName;
    private string $implication;
    private string $nationality;
    private string $country;
    private string $department;
    private string $departmentNumber;
    private string $city;
    private string $postCode;
    private string $inseeCode;
    private string $streetType;
    private string $streetNumber;
    private string $streetName;
    private string $address;
    private string $place;
    private string $phone;
    private string $email;
    private string $sameAddressAsDeclarant;

    public function __construct(Corporation $corporation, Identity $identity)
    {
        $this->siretNumber = $corporation->getSiretNumber() ?? '';
        $this->sirenNumber = $corporation->getSiretNumber() ? substr($corporation->getSiretNumber(), 0, 9) : '';
        $this->nicNumber = $corporation->getSiretNumber() ? substr($corporation->getSiretNumber(), 9) : '';
        $this->companyName = $corporation->getCompanyName() ?? '';
        $this->implication = $corporation->getDeclarantPosition() ?? '';
        $this->phone = $corporation->getPhone() ?? '';
        $this->nationality = $corporation->getNationality() ?? '';
        $hasSameAddressAsDeclarant = $corporation->hasSameAddressAsDeclarant();
        $this->country = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressCountry() : $corporation->getCountry());
        $this->department = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressDepartment() : $corporation->getDepartment());
        $this->departmentNumber = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressDepartmentNumber() : $corporation->getDepartmentNumber());
        $this->city = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressCity() : $corporation->getCity());
        $this->postCode = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressPostcode() : $corporation->getPostCode());
        $this->inseeCode = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressInseeCode() : $corporation->getInseeCode());
        $this->streetType = true === $hasSameAddressAsDeclarant ? $identity->getAddressStreetCompleteName() : $corporation->getStreetCompleteName();
        $this->streetNumber = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressStreetNumber() : $corporation->getStreetNumber());
        $this->streetName = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddressStreetName() : $corporation->getStreetName());
        $this->address = (string) (true === $hasSameAddressAsDeclarant ? $identity->getAddress() : $corporation->getAddress());
        $this->place = ($this->country ? strtoupper($this->country).', ' : '').($this->city ? strtoupper($this->city).', ' : '').($this->address ?: '');
        $this->email = $corporation->getContactEmail() ?? '';
        $this->sameAddressAsDeclarant = $hasSameAddressAsDeclarant ? 'Oui' : 'Non';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Personne_Morale' => [
            'Personne_Morale_Raison' => $this->companyName,
            'Personne_Morale_Num_Registre_Commerce' => '',
            'Personne_Morale_Siret' => $this->siretNumber,
            'Personne_Morale_Siren' => $this->sirenNumber,
            'Personne_Morale_NIC' => $this->nicNumber,
            'Personne_Morale_Implication' => $this->implication,
            'Tel_Bureau_Declarant' => $this->phone,
            'Personne_Morale_Nationalite' => $this->nationality,
            'Personne_Morale_Secteur' => '',
            'Personne_Morale_Juridique' => '',
            'Personne_Morale_Residence_Pays' => $this->country,
            'Personne_Morale_Residence_Departement' => $this->departmentNumber.' - '.$this->department,
            'Personne_Morale_Residence_Codepostal' => $this->postCode,
            'Personne_Morale_Residence_Insee' => $this->inseeCode,
            'Personne_Morale_Residence_Commune' => $this->city,
            'Personne_Morale_Residence_HidNumDep' => $this->departmentNumber,
            'Personne_Morale_Residence_RueNo' => $this->streetNumber,
            'Personne_Morale_Residence_RueType' => $this->streetType,
            'Personne_Morale_Residence_RueNom' => $this->streetName,
            'Personne_Morale_Residence_Adresse' => $this->address,
            'Personne_Morale_Residence_Lieu' => $this->place,
            'Personne_Morale_Residence_Identique' => $this->sameAddressAsDeclarant,
            'Mail_Personne_Morale' => $this->email,
        ]];
    }
}
