<?php

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\SimpleObject;

class SimpleObjectDTO extends AbstractObjectDTO
{
    private string $nature;
//    private string $brand;
//    private string $model;
//    private string $serialNumber;
    private string $description;

    public function __construct(SimpleObject $object)
    {
//        parent::__construct($object);
        $this->nature = $object->getNature() ?? '';
//        $this->brand = $object->getBrand() ?? '';
//        $this->model = $object->getModel() ?? '';
//        $this->serialNumber = $object->getSerialNumber() ?? '';
        $this->description = $object->getDescription() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_simple' => [
            'Objet_simple_Nature' => $this->nature,
//            'Objet_simple_Marque' => $this->brand,
//            'Objet_simple_Modele' => $this->model,
//            'Objet_simple_Numeros_Serie' => $this->serialNumber,
            'Objet_simple_Description' => $this->description,
//            'Objet_simple_Identite_Victime' => $this->identityVictim,
//            'Objet_simple_Identite_Nom' => $this->identityLastname,
//            'Objet_simple_Identite_Nom_Marital' => $this->identityMarriedName,
//            'Objet_simple_Identite_Prenom' => $this->identityFirstName,
//            'Objet_simple_Identite_Naissance_Date' => $this->identityBirthDate,
//            'Objet_simple_Identite_Naissance' => $this->identityBirthCountry,
//            'Objet_simple_Identite_Naissance_Departement' => ($this->identityBirthDepartmentNumber && $this->identityBirthDepartment) ? $this->identityBirthDepartmentNumber.' - '.$this->identityBirthDepartment : null,
//            'Objet_simple_Identite_Naissance_Codepostal' => $this->identityBirthPostalCode,
//            'Objet_simple_Identite_Naissance_Commune' => $this->identityBirthCity,
//            'Objet_simple_Identite_Naissance_Insee' => $this->identityBirthInseeCode,
//            'Objet_simple_Identite_Residence' => $this->identityCountry,
//            'Objet_simple_Identite_Residence_Departement' => ($this->identityDepartmentNumber && $this->identityDepartment) ? $this->identityDepartmentNumber.' - '.$this->identityDepartment : null,
//            'Objet_simple_Identite_Residence_Codepostal' => $this->identityPostalCode,
//            'Objet_simple_Identite_Residence_Commune' => $this->identityCity,
//            'Objet_simple_Identite_Residence_Insee' => $this->identityInseeCode,
//            'Objet_simple_Identite_Residence_RueNo' => $this->identityStreetNumber,
//            'Objet_simple_Identite_Residence_RueType' => $this->identityStreetType,
//            'Objet_simple_Identite_Residence_RueNom' => $this->identityStreetName,
//            'Objet_simple_Identite_Naissance_HidNumDep' => $this->identityBirthDepartmentNumber,
//            'Objet_simple_Identite_Residence_HidNumDep' => $this->identityDepartmentNumber,
//            'Objet_simple_Vol_Dans_Vl' => $this->theftFromVehicle,
        ]];
    }
}
