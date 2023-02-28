<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Identity;

class PersonDTO
{
//    private ?string $relationship;
    private ?string $implication;
    private string $civility;
    private string $lastname;
    private string $marriedName;
    private string $firstname;
    private string $birthdate;
    private string $birthCountry;
    private string $birthDepartment;
    private string $birthPostalCode;
    private string $birthInseeCode;
    private string $birthCity;
    private string $birthDepartmentNumber;
//    private string $familyStatus;
    private string $nationality;
    private string $job;
    private string $country;
    private string $departement;
    private string $postalCode;
    private string $inseeCode;
    private string $city;
    private string $departmentNumber;
    private string $streetNumber;
    private string $streetType;
    private string $streetName;
    private string $address;
    private string $place;
    private string $birthplace;

    public function __construct(Identity $identity)
    {
//        $this->relationship = $identity->getRelationshipWithVictime();
        $this->implication = Identity::DECLARANT_STATUS_VICTIM === $identity->getDeclarantStatus() ? 'victime' : (Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE === $identity->getDeclarantStatus() ? 'physique' : (Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $identity->getDeclarantStatus() ? 'morale' : null));
        $this->civility = Identity::CIVILITY_MALE === $identity->getCivility() ? 'M' : (Identity::CIVILITY_FEMALE === $identity->getCivility() ? 'Mme' : '');
        $this->lastname = $identity->getLastname() ?? '';
        $this->marriedName = $identity->getMarriedName() ?? '';
        $this->firstname = $identity->getFirstname() ?? '';
        $this->birthdate = $identity->getBirthday()?->format('d/m/Y') ?? '';
        $this->birthCountry = $identity->getBirthCountry() ?? '';
        $this->birthDepartment = $identity->getBirthDepartment() ?? '';
        $this->birthPostalCode = $identity->getBirthPostalCode() ?? '';
        $this->birthInseeCode = $identity->getBirthInseeCode() ?? '';
        $this->birthCity = $identity->getBirthCity() ?? '';
        $this->birthDepartmentNumber = !is_null($identity->getBirthDepartmentNumber()) ? strval($identity->getBirthDepartmentNumber()) : '';
//        $this->familyStatus = $identity->getFamilyStatus() ?? '';
        $this->nationality = $identity->getNationality() ?? '';
        $this->job = $identity->getJob() ?? '';
        $this->country = $identity->getAddressCountry() ?? '';
        $this->departement = $identity->getAddressDepartment() ?? '';
        $this->postalCode = $identity->getAddressPostcode() ?? '';
        $this->inseeCode = $identity->getAddressInseeCode() ?? '';
        $this->city = $identity->getAddressCity() ?? '';
        $this->departmentNumber = !is_null($identity->getAddressDepartmentNumber()) ? strval($identity->getAddressDepartmentNumber()) : '';
        $this->streetNumber = $identity->getAddressStreetNumber() ?? '';
        $this->streetType = $identity->getAddressStreetType() ?? '';
        $this->streetName = $identity->getAddressStreetName() ?? '';
        $this->address = $identity->getAddress() ?? '';

        if ($identity->getAddressCity() && $identity->getAddressPostcode() && $identity->getAddressCountry()) {
            $this->place = $identity->getAddressCity().' '.$identity->getAddressPostcode().' ('.$identity->getAddressCountry().')';
        } else {
            $this->place = '';
        }

        if ($identity->getBirthCity() && $identity->getBirthPostalCode() && $identity->getBirthCountry()) {
            $this->birthplace = $identity->getBirthCity().' '.$identity->getBirthPostalCode().' ('.$identity->getBirthCountry().')';
        } else {
            $this->birthplace = '';
        }
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Personne' => [
//            'Personne_Lien' => $this->relationship,
            'Personne_Implication' => $this->implication,
            'Personne_Civilite_Sexe' => $this->civility,
            'Personne_Nom' => $this->lastname,
            'Personne_Nom_Marital' => $this->marriedName,
            'Personne_Prenom' => $this->firstname,
            'Personne_Naissance_Date' => $this->birthdate,
            'Personne_Naissance_Pays' => $this->birthCountry,
            'Personne_Naissance_Departement' => $this->birthDepartmentNumber.' - '.$this->birthDepartment,
            'Personne_Naissance_Codepostal' => $this->birthPostalCode,
            'Personne_Naissance_Insee' => $this->birthInseeCode,
            'Personne_Naissance_Commune' => $this->birthCity,
            'Personne_Naissance_HidNumDep' => $this->birthDepartmentNumber,
//            'Personne_Situation_Familiale' => $this->familyStatus,
            'Personne_Nationalite' => $this->nationality,
            'Personne_Profession' => $this->job,
            'Personne_Residence_Pays' => $this->country,
            'Personne_Residence_Departement' => $this->departmentNumber.' - '.$this->departement,
            'Personne_Residence_Codepostal' => $this->postalCode,
            'Personne_Residence_Insee' => $this->inseeCode,
            'Personne_Residence_Commune' => $this->city,
            'Personne_Residence_HidNumDep' => $this->departmentNumber,
            'Personne_Residence_RueNo' => $this->streetNumber,
            'Personne_Residence_RueType' => $this->streetType,
            'Personne_Residence_RueNom' => $this->streetName,
            'Personne_Residence_Adresse' => $this->address,
            'Personne_Residence_Lieu' => $this->place,
            'Personne_Naissance_Lieu' => $this->birthplace,
        ]];
    }
}
