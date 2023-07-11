<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model;

use App\Entity\Identity;

class PersonDTO extends AbstractIdentityDTO
{
    //    private ?string $relationship;
    private ?string $implication;

    public function __construct(Identity $identity)
    {
        parent::__construct($identity);
        //        $this->relationship = $identity->getRelationshipWithVictime();
        $this->implication = Identity::DECLARANT_STATUS_VICTIM === $identity->getDeclarantStatus() ? 'victime' : (Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE === $identity->getDeclarantStatus() ? 'physique' : (Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $identity->getDeclarantStatus() ? 'morale' : null));
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Personne' => [
//            'Personne_Lien' => $this->relationship,
            'Personne_Implication' => $this->implication,
            'Personne_Telephone_Portable' => $this->mobilePhone,
            'Personne_Civilite_Sexe' => $this->civilStatus,
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
            'Personne_Situation_Familiale' => $this->familySituation,
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
            'Personne_Residence_Lieu' => $this->homePlace,
            'Personne_Naissance_Lieu' => $this->birthplace,
        ]];
    }
}
