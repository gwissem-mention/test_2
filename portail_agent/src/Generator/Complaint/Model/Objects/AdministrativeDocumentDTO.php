<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\Identity;

class AdministrativeDocumentDTO extends AbstractObjectDTO
{
    private string $type;
    private string $number;
    private string $issuedOn;
    private string $issuedBy;
    private ?string $ownerLastname;
    private ?string $ownerFirstname;
    private ?string $identityDepartmentNumber;
    private ?string $identityDepartment;
    private ?string $ownerPostcode;
    private ?string $ownerInseeCode;
    private ?string $ownerCity;
    private ?string $ownerStreetNumber;
    private ?string $ownerStreetType;
    private ?string $ownerStreetName;
    private ?string $identityBirthDate;
    private string $issuingCountry;
    private string $description;
    private ?string $identityCountry;
    private ?string $identityVictim;
    private ?string $endDateValidity;
    private ?string $status;
    private ?string $identityPhone;
    private ?string $identityMail;
    private ?string $identityResidenceDepartment;

    public function __construct(AdministrativeDocument $object, ?Identity $identity)
    {
        // parent::__construct($object);
        $this->type = $object->getType() ?? '';
        $this->number = $object->getNumber() ?? '';
        $this->issuedOn = $object->getIssuedOn() ? $object->getIssuedOn()->format('d/m/Y') : '';
        $this->issuedBy = $object->getIssuedBy() ?? '';
        $this->ownerLastname = $object->getOwnerLastname();
        $this->ownerFirstname = $object->getOwnerFirstname();
        $this->identityDepartment = $identity?->getAddressDepartment();
        $this->identityCountry = $identity?->getAddressCountry();
        $this->identityBirthDate = true === $object->isOwned() ? $identity?->getBirthday()?->format('d/m/Y') : '';
        $this->description = $this->getStatusAsString((int) $object->getStatus()).' - '.$object->getDescription();
        $this->issuingCountry = $object->getIssuingCountry() ?? '';
        $this->identityVictim = $object->isOwned() ? 'Oui' : 'Non';
        $this->endDateValidity = $object->getValidityEndDate()?->format('d/m/Y');
        $this->status = AbstractObject::STATUS_STOLEN === $object->getStatus() ? 'volé' : 'dégradé';
        $this->identityPhone = $identity?->getMobilePhone() ?? '';
        $this->identityMail = $identity?->getEmail() ?? '';
        switch (true) {
            case $object->isOwned():
                $this->identityDepartmentNumber = (string) $identity?->getAddressDepartmentNumber();
                $this->ownerStreetName = $identity?->getAddressStreetName();
                $this->ownerStreetType = '';
                $this->ownerStreetNumber = $identity?->getAddressStreetNumber();
                $this->ownerInseeCode = $identity?->getAddressInseeCode();
                $this->ownerCity = $identity?->getAddressCity();
                $this->ownerPostcode = $identity?->getAddressPostcode();
                $this->identityResidenceDepartment = ($this->identityDepartmentNumber && $this->identityDepartment && 'France' === $this->identityCountry) ? $this->identityDepartmentNumber.' - '.$this->identityDepartment : null;
                break;

            default:
                $this->identityDepartmentNumber = (string) $object->getOwnerAddressDepartmentNumber();
                $this->ownerStreetName = $object->getOwnerAddressStreetName();
                $this->ownerStreetType = '';
                $this->ownerStreetNumber = $object->getOwnerAddressStreetNumber();
                $this->ownerInseeCode = $object->getOwnerAddressInseeCode();
                $this->ownerCity = $object->getOwnerAddressCity();
                $this->ownerPostcode = $object->getOwnerAddressPostcode();
                $this->identityResidenceDepartment = ($this->identityDepartmentNumber && $object->getOwnerAddressDepartment() && 'France' === $object->getIssuingCountry()) ? $this->identityDepartmentNumber.' - '.$object->getOwnerAddressDepartment() : null;
                break;
        }
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_Doc_Admin' => [
            'Objet_Doc_Admin_Pays_Delivrance' => $this->issuingCountry,
            'Objet_Doc_Admin_Type' => $this->type,
            'Objet_Doc_Admin_Numero' => $this->number,
            'Objet_Doc_Admin_Date_Delivrance' => $this->issuedOn,
            'Objet_Doc_Admin_Autorite' => $this->issuedBy,
            'Objet_Doc_Admin_Description' => $this->description,
            'Objet_Doc_Admin_Identite_Victime' => $this->identityVictim,
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
            'Objet_Doc_Admin_Identite_Residence_Departement' => $this->identityResidenceDepartment,
            'Objet_Doc_Admin_Identite_Residence_Codepostal' => $this->ownerPostcode,
            'Objet_Doc_Admin_Identite_Residence_Commune' => $this->ownerCity,
            'Objet_Doc_Admin_Identite_Residence_Insee' => $this->ownerInseeCode,
            'Objet_Doc_Admin_Identite_Residence_RueNo' => $this->ownerStreetNumber,
            'Objet_Doc_Admin_Identite_Residence_RueType' => $this->ownerStreetType,
            'Objet_Doc_Admin_Identite_Residence_RueNom' => $this->ownerStreetName,
//             'Objet_Doc_Admin_Identite_Naissance_HidNumDep' => $this->identityBirthDepartmentNumber,
            'Objet_Doc_Admin_Identite_Residence_HidNumDep' => $this->identityDepartmentNumber,
            // 'Objet_Doc_Admin_Vol_Dans_Vl' => $this->theftFromVehicle,
            'Objet_Doc_Admin_Date_Fin_Validite' => $this->endDateValidity,
            'Objet_Doc_Admin_Statut' => $this->status,
            'Objet_Doc_Admin_Identite_Tel' => $this->identityPhone,
            'Objet_Doc_Admin_Identite_Mail' => $this->identityMail,
        ]];
    }
}
