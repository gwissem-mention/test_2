<?php

namespace App\Generator\Complaint\Model;

use App\Entity\Identity;

class PersonLegalRepresentativeDTO extends AbstractIdentityDTO
{
    public function __construct(Identity $identity)
    {
        parent::__construct($identity);
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Representant_Legal' => [
            'Representant_Legal_Civilite_Sexe' => $this->civilStatus,
            'Representant_Legal_Nom' => $this->lastname,
            'Representant_Legal_Nom_Marital' => $this->marriedName,
            'Representant_Legal_Prenom' => $this->firstname,
            'Representant_Legal_Naissance_Date' => $this->birthdate,
            'Representant_Legal_Naissance_Pays' => $this->birthCountry,
            'Representant_Legal_Naissance_Departement' => $this->birthDepartmentNumber.' - '.$this->birthDepartment,
            'Representant_Legal_Naissance_Codepostal' => $this->birthPostalCode,
            'Representant_Legal_Naissance_Insee' => $this->birthInseeCode,
            'Representant_Legal_Naissance_Commune' => $this->birthCity,
            'Representant_Legal_Naissance_HidNumDep' => $this->birthDepartmentNumber,
            'Representant_Legal_Nationalite' => $this->nationality,
            'Representant_Legal_Profession' => $this->job,
            'Representant_Legal_Residence_Pays' => $this->country,
            'Representant_Legal_Residence_Departement' => $this->departmentNumber.' - '.$this->departement,
            'Representant_Legal_Residence_Codepostal' => $this->postalCode,
            'Representant_Legal_Residence_Insee' => $this->inseeCode,
            'Representant_Legal_Residence_Commune' => $this->city,
            'Representant_Legal_Residence_HidNumDep' => $this->departmentNumber,
            'Representant_Legal_Residence_RueNo' => $this->streetNumber,
            'Representant_Legal_Residence_RueType' => $this->streetType,
            'Representant_Legal_Residence_RueNom' => $this->streetName,
            'Representant_Legal_Residence_Adresse' => $this->address,
            'Representant_Legal_Residence_Lieu' => $this->place,
            'Representant_Legal_Naissance_Lieu' => $this->birthplace,
        ]];
    }
}
