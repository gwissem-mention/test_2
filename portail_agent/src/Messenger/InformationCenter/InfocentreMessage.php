<?php

declare(strict_types=1);

namespace App\Messenger\InformationCenter;

use App\Entity\Complaint;
use App\Referential\Entity\Unit;

final class InfocentreMessage
{
    private ?\DateTimeImmutable $declarationDate;
    private ?string $unitCode;
    private ?string $serviceCode;
    private string $declarationNumber;
    private ?string $declarationStatus;
    private ?string $DeclarationTown;
    private ?string $agentCode;
    private \DateTimeImmutable $actionDate;
    private bool $isFranceConnected;
    private bool $isPersonLegalRepresented;
    private bool $isVictime;
    private bool $isCorporationRepresented;
    private bool $withAlert;
    private bool $withAppointment;
    private ?string $institution;
    private string $actionType;
    private ?string $unitAddress;
    private ?string $unitName;
    private ?string $unitPhone;
    private bool $isVictimOfViolence;

    public function __construct(string $action, Complaint $complaint, ?Unit $unit)
    {
        $this->declarationNumber = $complaint->getDeclarationNumber();
        $this->declarationStatus = $complaint->getStatus();
        $this->declarationDate = $complaint->getCreatedAt();
        $this->unitCode = $unit?->getCode();
        $this->unitAddress = $unit?->getAddress();
        $this->unitName = $unit?->getName();
        $this->unitPhone = $unit?->getPhone();
        $this->serviceCode = $complaint->getAssignedTo()?->getServiceCode();
        $this->agentCode = $complaint->getAssignedTo()?->getNumber();
        $this->actionDate = new \DateTimeImmutable();
        $this->DeclarationTown = $complaint->getIdentity()?->getAddressCity();
        $this->isFranceConnected = $complaint->isFranceConnected();
        $this->isPersonLegalRepresented = null !== $complaint->getPersonLegalRepresented();
        $this->isVictime = null !== $complaint->getPersonLegalRepresented() && null !== $complaint->getCorporationRepresented();
        $this->isCorporationRepresented = null !== $complaint->getCorporationRepresented();
        $this->withAlert = null !== $complaint->getAlert();
        $this->withAppointment = null !== $complaint->getAppointmentDate();
        $this->isVictimOfViolence = null !== $complaint->getFacts()?->isVictimOfViolence();
        $this->institution = $complaint->getAssignedTo()?->getInstitution()->name;
        $this->actionType = $action;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'declarationNumber' => $this->declarationNumber,
            'declarationStatus' => $this->declarationStatus,
            'declarationDate' => $this->declarationDate,
            'unitName' => $this->unitName,
            'unitCode' => $this->unitCode,
            'unitAddress' => $this->unitAddress,
            'unitPhone' => $this->unitPhone,
            'serviceCode' => $this->serviceCode,
            'agentCode' => $this->agentCode,
            'actionDate' => $this->actionDate,
            'declarationTown' => $this->DeclarationTown,
            'isFranceConnected' => $this->isFranceConnected,
            'isPersonLegalRepresented' => $this->isPersonLegalRepresented,
            'isVictime' => $this->isVictime,
            'isCorporationRepresented' => $this->isCorporationRepresented,
            'withAlert' => $this->withAlert,
            'withAppointment' => $this->withAppointment,
            'isVictimOfViolence' => $this->isVictimOfViolence,
            'institution' => $this->institution,
            'action' => $this->actionType,
        ];
    }
}
