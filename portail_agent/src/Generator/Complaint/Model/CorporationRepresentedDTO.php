<?php

namespace App\Generator\Complaint\Model;

use App\Entity\Corporation;

class CorporationRepresentedDTO
{
    private string $sirenNumber;
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

    public function __construct(Corporation $corporation)
    {
        $this->sirenNumber = $corporation->getSirenNumber() ?? '';
        $this->companyName = $corporation->getCompanyName() ?? '';
        $this->implication = $corporation->getDeclarantPosition() ?? '';
        $this->phone = $corporation->getPhone() ?? '';
        $this->nationality = $corporation->getNationality() ?? '';
        $this->country = $corporation->getCountry() ?? '';
        $this->department = $corporation->getDepartment() ?? '';
        $this->departmentNumber = (string) $corporation->getDepartmentNumber();
        $this->city = $corporation->getCity() ?? '';
        $this->postCode = $corporation->getPostCode() ?? '';
        $this->inseeCode = $corporation->getInseeCode() ?? '';
        $this->streetType = $corporation->getStreetType() ?? '';
        $this->streetNumber = (string) $corporation->getStreetNumber();
        $this->streetName = $corporation->getStreetName() ?? '';
        $this->address = $corporation->getAddress() ?? '';
        $this->place = ($corporation->getCity() ? strtoupper($corporation->getCity()).' ' : '').($corporation->getPostCode() ? $corporation->getPostCode().' ' : '').($corporation->getCountry() ? '('.$corporation->getCountry().')' : '');
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Personne_Morale' => [
            'Personne_Morale_Raison' => $this->companyName,
            'Personne_Morale_Num_Registre_Commerce' => '',
            'Personne_Morale_Siret' => $this->sirenNumber,
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
        ]];
    }
}
