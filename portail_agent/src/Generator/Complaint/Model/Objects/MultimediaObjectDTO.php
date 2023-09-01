<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\MultimediaObject;

class MultimediaObjectDTO extends AbstractObjectDTO
{
    private string $nature;
    private string $serialNumber;
    private string $description;
    private ?string $phoneNumber;
    private ?string $operator;
    //    private ?string $opposition;
    //    private ?string $simNumber;

    public function __construct(MultimediaObject $object)
    {
        // parent::__construct($object);
        $this->nature = $object->getNature() ?? '';
        $this->serialNumber = $object->getSerialNumber() ? strval($object->getSerialNumber()) : '';
        $this->description = $this->getStatusAsString((int) $object->getStatus()).' - '.$object->getDescription();
        $this->phoneNumber = $object->getPhoneNumber();
        $this->operator = $object->getOperator();
        //        $this->opposition = $object->isOpposition() ? 'Oui' : 'Non';
        //        $this->simNumber = $object->getSimNumber();
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_Multimedia' => [
            'Objet_Multimedia_Nature' => $this->nature,
            'Objet_Multimedia_Numeros_Serie' => $this->serialNumber,
            'Objet_Multimedia_Description' => $this->description,
            'Objet_Multimedia_Nmr_Tel' => $this->phoneNumber,
            'Objet_Multimedia_Operateur' => $this->operator,
            'Objet_Multimedia_Identite_Victime' => 'Oui',
//            'Objet_Multimedia_Opposition' => $this->opposition,
//            'Objet_Multimedia_Nmr_Sim' => $this->simNumber,
            // 'Objet_Multimedia_Identite_Victime' => $this->identityVictim,
            // 'Objet_Multimedia_Identite_Nom' => $this->identityLastname,
            // 'Objet_Multimedia_Identite_Nom_Marital' => $this->identityMarriedName,
            // 'Objet_Multimedia_Identite_Prenom' => $this->identityFirstName,
            // 'Objet_Multimedia_Identite_Naissance_Date' => $this->identityBirthDate,
            // 'Objet_Multimedia_Identite_Naissance' => $this->identityBirthCountry,
            // 'Objet_Multimedia_Identite_Naissance_Departement' => ($this->identityBirthDepartmentNumber && $this->identityBirthDepartment) ? $this->identityBirthDepartmentNumber.' - '.$this->identityBirthDepartment : null,
            // 'Objet_Multimedia_Identite_Naissance_Codepostal' => $this->identityBirthPostalCode,
            // 'Objet_Multimedia_Identite_Naissance_Commune' => $this->identityBirthCity,
            // 'Objet_Multimedia_Identite_Naissance_Insee' => $this->identityBirthInseeCode,
            // 'Objet_Multimedia_Identite_Residence' => $this->identityCountry,
            // 'Objet_Multimedia_Identite_Residence_Departement' => ($this->identityDepartmentNumber && $this->identityDepartment) ? $this->identityDepartmentNumber.' - '.$this->identityDepartment : null,
            // 'Objet_Multimedia_Identite_Residence_Codepostal' => $this->identityPostalCode,
            // 'Objet_Multimedia_Identite_Residence_Commune' => $this->identityCity,
            // 'Objet_Multimedia_Identite_Residence_Insee' => $this->identityInseeCode,
            // 'Objet_Multimedia_Identite_Residence_RueNo' => $this->identityStreetNumber,
            // 'Objet_Multimedia_Identite_Residence_RueType' => $this->identityStreetType,
            // 'Objet_Multimedia_Identite_Residence_RueNom' => $this->identityStreetName,
            // 'Objet_Multimedia_Identite_Naissance_HidNumDep' => $this->identityBirthDepartmentNumber,
            // 'Objet_Multimedia_Identite_Residence_HidNumDep' => $this->identityDepartmentNumber,
            // 'Objet_Multimedia_Vol_Dans_Vl' => $this->theftFromVehicle,
        ]];
    }
}
