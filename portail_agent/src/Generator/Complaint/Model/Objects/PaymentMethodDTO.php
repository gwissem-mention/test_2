<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\Identity;

class PaymentMethodDTO extends AbstractObjectDTO
{
    private string $type;
    // private string $currency;
    // private ?string $number;
    private string $description;
    // private ?string $opposition;
    private ?string $bank;
    private string $bankAccountNumber;
    // private ?string $cardType;
    private ?string $chequeNumber;
    private ?string $firstChequeNumber;
    private ?string $lastChequeNumber;
    private string $status;
    private string $creditCardNumber;
    private string $identityLastname;
    private string $identityFirstname;

    public function __construct(PaymentMethod $object, ?Identity $identity)
    {
        // parent::__construct($object);
        $this->type = $object->getType() ?? '';
        // $this->currency = $object->getCurrency() ?? '';
        // $this->number = $object->getNumber();
        $this->description = $object->getDescription() ?? '';
        // $this->opposition = $object->isOpposition() ? 'Oui' : 'Non';
        $this->bank = $object->getBank() ?? '';
        $this->bankAccountNumber = $object->getBankAccountNumber() ?? '';
        // $this->cardType = $object->getCardType();
        $this->chequeNumber = $object->getChequeNumber();
        $this->firstChequeNumber = $object->getFirstChequeNumber();
        $this->lastChequeNumber = $object->getLastChequeNumber();
        $this->status = AbstractObject::STATUS_STOLEN === $object->getStatus() ? 'Volé' : 'Dégradé';
        $this->creditCardNumber = (string) $object->getCreditCardNumber();
        $this->identityLastname = Identity::DECLARANT_STATUS_VICTIM === $identity?->getDeclarantStatus() ? $identity->getLastname() ?? '' : '';
        $this->identityFirstname = Identity::DECLARANT_STATUS_VICTIM === $identity?->getDeclarantStatus() ? $identity->getFirstname() ?? '' : '';
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getArray(): array
    {
        return ['Objet_Moyen_Paiement' => [
            'Objet_Moyen_Paiement_Type' => $this->type,
            // 'Objet_Moyen_Paiement_Devise' => $this->currency,
            // 'Objet_Moyen_Paiement_Numero' => $this->number,
            'Objet_Moyen_Paiement_Description' => $this->description,
            'Objet_Moyen_Paiement_Descript' => $this->description,
            // 'Objet_Moyen_Paiement_Opposition' => $this->opposition,
             'Objet_Moyen_Paiement_Banque' => $this->bank,
            'Objet_Moyen_Paiement_IBAN' => $this->bankAccountNumber,
            // 'Objet_Moyen_Paiement_Type_Carte' => $this->cardType,
            'Objet_Moyen_Paiement_Nmr' => $this->chequeNumber,
            'Objet_Moyen_Paiement_Premier_Nmr' => $this->firstChequeNumber,
            'Objet_Moyen_Paiement_Dernier_Nmr' => $this->lastChequeNumber,
            'Objet_Moyen_Paiement_Identite_Victime' => 'Oui',
            'Objet_Moyen_Paiement_Statut' => $this->status,
            'Objet_Moyen_Paiement_Numero' => $this->creditCardNumber,
            'Objet_Moyen_Paiement_Identite_Nom' => $this->identityLastname,
            // 'Objet_Moyen_Paiement_Identite_Nom_Marital' => $this->identityMarriedName,
             'Objet_Moyen_Paiement_Identite_Prenom' => $this->identityFirstname,
            // 'Objet_Moyen_Paiement_Identite_Naissance_Date' => $this->identityBirthDate,
            // 'Objet_Moyen_Paiement_Identite_Naissance' => $this->identityBirthCountry,
            // 'Objet_Moyen_Paiement_Identite_Naissance_Departement' => ($this->identityBirthDepartmentNumber && $this->identityBirthDepartment) ? $this->identityBirthDepartmentNumber.' - '.$this->identityBirthDepartment : null,
            // 'Objet_Moyen_Paiement_Identite_Naissance_Codepostal' => $this->identityBirthPostalCode,
            // 'Objet_Moyen_Paiement_Identite_Naissance_Commune' => $this->identityBirthCity,
            // 'Objet_Moyen_Paiement_Identite_Naissance_Insee' => $this->identityBirthInseeCode,
            // 'Objet_Moyen_Paiement_Identite_Residence' => $this->identityCountry,
            // 'Objet_Moyen_Paiement_Identite_Residence_Departement' => ($this->identityDepartmentNumber && $this->identityDepartment) ? $this->identityDepartmentNumber.' - '.$this->identityDepartment : null,
            // 'Objet_Moyen_Paiement_Identite_Residence_Codepostal' => $this->identityPostalCode,
            // 'Objet_Moyen_Paiement_Identite_Residence_Commune' => $this->identityCity,
            // 'Objet_Moyen_Paiement_Identite_Residence_Insee' => $this->identityInseeCode,
            // 'Objet_Moyen_Paiement_Identite_Residence_RueNo' => $this->identityStreetNumber,
            // 'Objet_Moyen_Paiement_Identite_Residence_RueType' => $this->identityStreetType,
            // 'Objet_Moyen_Paiement_Identite_Residence_RueNom' => $this->identityStreetName,
            // 'Objet_Moyen_Paiement_Identite_Naissance_HidNumDep' => $this->identityBirthDepartmentNumber,
            // 'Objet_Moyen_Paiement_Identite_Residence_HidNumDep' => $this->identityDepartmentNumber,
            // 'Objet_Moyen_Paiement_Vol_Dans_Vl' => $this->theftFromVehicle,
        ]];
    }
}
