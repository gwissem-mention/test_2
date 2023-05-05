<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AdministrativeDocument;

class AdministrativeDocumentDTO extends AbstractObjectDTO
{
    private string $type;
    //    private string $issuingCountry;
    //    private string $number;
    //    private string $deliveryDate;
    //    private string $authority;
    //    private string $description;

    public function __construct(AdministrativeDocument $object)
    {
        // parent::__construct($object);
        $this->type = $object->getType() ?? '';
        //        $this->number = $object->getNumber() ?? '';
        //        $this->description = $object->getDescription() ?? '';
        //        $this->issuingCountry = $object->getIssuingCountry() ?? '';
        //        $this->deliveryDate = $object->getDeliveryDate() ? $object->getDeliveryDate()->format('d/m/Y') : '';
        //        $this->authority = $object->getAuthority() ?? '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_Doc_Admin' => [
//            'Objet_Doc_Admin_Pays_Delivrance' => $this->issuingCountry,
            'Objet_Doc_Admin_Type' => $this->type,
//            'Objet_Doc_Admin_Numero' => $this->number,
//            'Objet_Doc_Admin_Date_Delivrance' => $this->deliveryDate,
//            'Objet_Doc_Admin_Autorite' => $this->authority,
//            'Objet_Doc_Admin_Description' => $this->description,
            // 'Objet_Doc_Admin_Identite_Victime' => $this->identityVictim,
            // 'Objet_Doc_Admin_Identite_Nom' => $this->identityLastname,
            // 'Objet_Doc_Admin_Identite_Nom_Marital' => $this->identityMarriedName,
            // 'Objet_Doc_Admin_Identite_Prenom' => $this->identityFirstName,
            // 'Objet_Doc_Admin_Identite_Naissance_Date' => $this->identityBirthDate,
            // 'Objet_Doc_Admin_Identite_Naissance' => $this->identityBirthCountry,
            // 'Objet_Doc_Admin_Identite_Naissance_Departement' => ($this->identityBirthDepartmentNumber && $this->identityBirthDepartment) ? $this->identityBirthDepartmentNumber.' - '.$this->identityBirthDepartment : null,
            // 'Objet_Doc_Admin_Identite_Naissance_Codepostal' => $this->identityBirthPostalCode,
            // 'Objet_Doc_Admin_Identite_Naissance_Commune' => $this->identityBirthCity,
            // 'Objet_Doc_Admin_Identite_Naissance_Insee' => $this->identityBirthInseeCode,
            // 'Objet_Doc_Admin_Identite_Residence' => $this->identityCountry,
            // 'Objet_Doc_Admin_Identite_Residence_Departement' => ($this->identityDepartmentNumber && $this->identityDepartment) ? $this->identityDepartmentNumber.' - '.$this->identityDepartment : null,
            // 'Objet_Doc_Admin_Identite_Residence_Codepostal' => $this->identityPostalCode,
            // 'Objet_Doc_Admin_Identite_Residence_Commune' => $this->identityCity,
            // 'Objet_Doc_Admin_Identite_Residence_Insee' => $this->identityInseeCode,
            // 'Objet_Doc_Admin_Identite_Residence_RueNo' => $this->identityStreetNumber,
            // 'Objet_Doc_Admin_Identite_Residence_RueType' => $this->identityStreetType,
            // 'Objet_Doc_Admin_Identite_Residence_RueNom' => $this->identityStreetName,
            // 'Objet_Doc_Admin_Identite_Naissance_HidNumDep' => $this->identityBirthDepartmentNumber,
            // 'Objet_Doc_Admin_Identite_Residence_HidNumDep' => $this->identityDepartmentNumber,
            // 'Objet_Doc_Admin_Vol_Dans_Vl' => $this->theftFromVehicle,
        ]];
    }
}
