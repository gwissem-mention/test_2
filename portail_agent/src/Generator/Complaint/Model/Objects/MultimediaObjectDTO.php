<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\MultimediaObject;

class MultimediaObjectDTO extends AbstractObjectDTO
{
    private string $nature;
    private string $serialNumber;
    private string $description;
    private string $descript;
    private ?string $phoneNumber;
    private ?string $operator;
    //    private ?string $opposition;
    //    private ?string $simNumber;
    private ?string $phoneStatus = null;
    private ?string $status = null;
    private ?string $phoneBrand = null;
    private ?string $brand = null;
    private ?string $phoneModel = null;
    private ?string $model = null;
    private ?string $amount;
    private ?string $phoneDescription = null;
    private ?string $phoneSerialNumber = null;
    private ?string $phoneIMEI = null;
    private ?string $IMEI;
    private string $serialNumbers;
    private ?string $phoneSerialNumbers = null;

    public function __construct(MultimediaObject $object)
    {
        // parent::__construct($object);
        $this->nature = $object->getNature() ?? '';
        $this->serialNumber = $object->getSerialNumber() ?? '';
        $this->serialNumbers = $object->getImei() ?? 'INCONNU';
        $this->phoneNumber = $object->getPhoneNumber() ? str_replace(' ', '', $object->getPhoneNumber()) : '';
        $this->operator = $object->getOperator();
        $this->description = $this->getStatusAsString((int) $object->getStatus()).' - '.$object->getDescription();
        $this->descript = $object->getDescription() ?? '';
        $this->IMEI = $object->getImei() ?? '';
        switch ($object->getNature()) {
            case 'TELEPHONE PORTABLE':
                $this->phoneStatus = AbstractObject::STATUS_STOLEN === $object->getStatus() ? 'volé' : 'dégradé';
                $this->phoneBrand = $object->getBrand();
                $this->phoneModel = $object->getModel();
                $this->phoneDescription = $object->getDescription().'. '.$object->getBrand().' '.$object->getModel();
                $this->phoneSerialNumber = $object->getSerialNumber() ?? '';
                $this->phoneSerialNumbers = $object->getImei() ?? 'INCONNU';
                $this->phoneIMEI = $object->getImei();
                $this->serialNumber = $this->IMEI = $this->description = $this->serialNumbers = '';
                break;
            case 'MULTIMEDIA':
                $this->status = AbstractObject::STATUS_STOLEN === $object->getStatus() ? 'volé' : 'dégradé';
                $this->brand = $object->getBrand();
                $this->model = $object->getModel();
                break;
        }
        //        $this->opposition = $object->isOpposition() ? 'Oui' : 'Non';
        //        $this->simNumber = $object->getSimNumber();
        $this->amount = (string) $object->getAmount();
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_Multimedia' => [
            'Objet_Multimedia_Nature' => $this->nature,
            'Objet_Multimedia_Numeros_Serie' => $this->serialNumbers,
            'Objet_Multimedia_Description' => $this->description,
            'Objet_Multimedia_Descript' => $this->descript,
            'Objet_Multimedia_Nmr_Tel' => $this->phoneNumber,
            'Objet_Multimedia_Operateur' => $this->operator,
            'Objet_Multimedia_Identite_Victime' => 'Oui',
            'Objet_Multimedia_Statut_Tel' => $this->phoneStatus,
            'Objet_Multimedia_Marque_Tel' => $this->phoneBrand,
            'Objet_Multimedia_Modele_Tel' => $this->phoneModel,
            'Objet_Multimedia_Statut' => $this->status,
            'Objet_Multimedia_Marque' => $this->brand,
            'Objet_Multimedia_Modele' => $this->model,
            'Objet_Multimedia_Prejudice_Estimation' => $this->amount,
            'Objet_Multimedia_Description_Tel' => $this->phoneDescription,
            'Objet_Multimedia_Numeros_Serie_Tel' => $this->phoneSerialNumbers,
            'Objet_Multimedia_IMEI_Tel' => $this->phoneIMEI,
            'Objet_Multimedia_IMEI' => $this->IMEI,
            'Objet_Multimedia_Num_Serie_Tel' => $this->phoneSerialNumber,
            'Objet_Multimedia_Num_Serie' => $this->serialNumber,
            // 'Objet_Multimedia_Opposition' => $this->opposition,
            // 'Objet_Multimedia_Nmr_Sim' => $this->simNumber,
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
