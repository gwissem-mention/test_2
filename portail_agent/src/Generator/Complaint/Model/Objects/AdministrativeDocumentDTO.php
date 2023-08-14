<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AdministrativeDocument;

class AdministrativeDocumentDTO extends AbstractObjectDTO
{
    private string $type;
    private string $number;
    private string $issuedOn;
    private string $issuedBy;
    private ?string $ownerLastname;
    private ?string $ownerFirstname;
    private ?string $ownerDepartmentNumber;
    private ?string $ownerDepartment;
    private ?string $ownerPostcode;
    private ?string $ownerInseeCode;
    private ?string $ownerCity;
    private ?string $ownerStreetNumber;
    private ?string $ownerStreetType;
    private ?string $ownerStreetName;

    private ?string $identityBirthDate;

    //    private string $issuingCountry;
    //    private string $description;

    public function __construct(AdministrativeDocument $object, ?\DateTimeInterface $identityBirthDate)
    {
        // parent::__construct($object);
        $this->type = $object->getType() ?? '';
        $this->number = $object->getNumber() ?? '';
        $this->issuedOn = $object->getIssuedOn() ? $object->getIssuedOn()->format('d/m/Y') : '';
        $this->issuedBy = $object->getIssuedBy() ?? '';
        $this->ownerLastname = $object->getOwnerLastname();
        $this->ownerFirstname = $object->getOwnerFirstname();
        $this->ownerDepartmentNumber = $object->getOwnerAddressDepartmentNumber();
        $this->ownerDepartment = $object->getOwnerAddressDepartment();
        $this->ownerPostcode = $object->getOwnerAddressPostcode();
        $this->ownerInseeCode = $object->getOwnerAddressInseeCode();
        $this->ownerCity = $object->getOwnerAddressCity();
        $this->ownerStreetNumber = $object->getOwnerAddressStreetNumber();
        $this->ownerStreetType = $object->getOwnerAddressStreetType();
        $this->ownerStreetName = $object->getOwnerAddressStreetName();
        $this->identityBirthDate = $identityBirthDate?->format('d/m/Y');
        //        $this->description = $object->getDescription() ?? '';
        //        $this->issuingCountry = $object->getIssuingCountry() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_Doc_Admin' => [
//            'Objet_Doc_Admin_Pays_Delivrance' => $this->issuingCountry,
            'Objet_Doc_Admin_Type' => $this->type,
            'Objet_Doc_Admin_Numero' => $this->number,
            'Objet_Doc_Admin_Date_Delivrance' => $this->issuedOn,
            'Objet_Doc_Admin_Autorite' => $this->issuedBy,
//            'Objet_Doc_Admin_Description' => $this->description,
            // 'Objet_Doc_Admin_Identite_Victime' => $this->identityVictim,
             'Objet_Doc_Admin_Identite_Nom' => $this->ownerLastname,
//             'Objet_Doc_Admin_Identite_Nom_Marital' => $this->identityMarriedName,
             'Objet_Doc_Admin_Identite_Prenom' => $this->ownerFirstname,
            'Objet_Doc_Admin_Identite_Naissance_Date' => $this->identityBirthDate,
            // 'Objet_Doc_Admin_Identite_Naissance' => $this->identityBirthCountry,
            // 'Objet_Doc_Admin_Identite_Naissance_Departement' => ($this->identityBirthDepartmentNumber && $this->identityBirthDepartment) ? $this->identityBirthDepartmentNumber.' - '.$this->identityBirthDepartment : null,
            // 'Objet_Doc_Admin_Identite_Naissance_Codepostal' => $this->identityBirthPostalCode,
            // 'Objet_Doc_Admin_Identite_Naissance_Commune' => $this->identityBirthCity,
            // 'Objet_Doc_Admin_Identite_Naissance_Insee' => $this->identityBirthInseeCode,
            // 'Objet_Doc_Admin_Identite_Residence' => $this->identityCountry,
             'Objet_Doc_Admin_Identite_Residence_Departement' => ($this->ownerDepartmentNumber && $this->ownerDepartment) ? $this->ownerDepartmentNumber.' - '.$this->ownerDepartment : null,
             'Objet_Doc_Admin_Identite_Residence_Codepostal' => $this->ownerPostcode,
             'Objet_Doc_Admin_Identite_Residence_Commune' => $this->ownerCity,
             'Objet_Doc_Admin_Identite_Residence_Insee' => $this->ownerInseeCode,
             'Objet_Doc_Admin_Identite_Residence_RueNo' => $this->ownerStreetNumber,
             'Objet_Doc_Admin_Identite_Residence_RueType' => $this->ownerStreetType,
             'Objet_Doc_Admin_Identite_Residence_RueNom' => $this->ownerStreetName,
//             'Objet_Doc_Admin_Identite_Naissance_HidNumDep' => $this->identityBirthDepartmentNumber,
             'Objet_Doc_Admin_Identite_Residence_HidNumDep' => $this->ownerDepartmentNumber,
            // 'Objet_Doc_Admin_Vol_Dans_Vl' => $this->theftFromVehicle,
        ]];
    }
}
