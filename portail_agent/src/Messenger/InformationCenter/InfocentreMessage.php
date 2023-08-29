<?php

declare(strict_types=1);

namespace App\Messenger\InformationCenter;

use App\Entity\Complaint;
use App\Referential\Entity\Unit;
use Symfony\Component\Clock\ClockAwareTrait;

final class InfocentreMessage
{
    use ClockAwareTrait;
    private ?\DateTimeImmutable $declarationDate;
    private ?string $unitCode;
    private ?string $serviceCode;
    private string $declarationNumber;
    private ?string $declarationStatus;
    private ?string $declarationTown;
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
    private bool $isStolenVehicle;
    private ?string $placeNature;
    private ?string $refusalReason;

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
        $this->actionDate = $this->now();
        $this->declarationTown = $complaint->getIdentity()?->getAddressInseeCode();
        $this->isFranceConnected = $complaint->isFranceConnected();
        $this->isPersonLegalRepresented = null !== $complaint->getPersonLegalRepresented();
        $this->isVictime = null !== $complaint->getPersonLegalRepresented() && null !== $complaint->getCorporationRepresented();
        $this->isCorporationRepresented = null !== $complaint->getCorporationRepresented();
        $this->withAlert = null !== $complaint->getAlert();
        $this->withAppointment = null !== $complaint->getAppointmentDate();
        $this->isVictimOfViolence = null !== $complaint->getFacts()?->isVictimOfViolence();
        $this->institution = $complaint->getAssignedTo()?->getInstitution()->name;
        $this->actionType = $action;
        $this->isStolenVehicle = $complaint->isStolenVehicle();
        $this->placeNature = $complaint->getFacts()?->getPlace();
        $this->refusalReason = $complaint->getRefusalReason();
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'declaration' => [
                'declarationNumber' => $this->declarationNumber,
                'declarationStatus' => $this->declarationStatus,
                'declarationDate' => $this->declarationDate,
                'refusalReason' => $this->refusalReason,
                'withAlert' => $this->withAlert,
                'withAppointment' => $this->withAppointment,
            ],
            'identity' => [
                'declarationTown' => $this->declarationTown,
                'isFranceConnected' => $this->isFranceConnected,
                'isPersonLegalRepresented' => $this->isPersonLegalRepresented,
                'isVictime' => $this->isVictime,
                'isCorporationRepresented' => $this->isCorporationRepresented,
            ],
            'facts' => [
                'placeNature' => $this->placeNature,
                'isVictimOfViolence' => $this->isVictimOfViolence,
                'isStolenVehicle' => $this->isStolenVehicle,
            ],
            'affectation' => [
                'unitName' => $this->unitName,
                'unitCode' => $this->unitCode,
                'unitAddress' => $this->unitAddress,
                'unitPhone' => $this->unitPhone,
                'serviceCode' => $this->serviceCode,
                'agentCode' => $this->agentCode,
                'institution' => $this->institution,
            ],
            'action' => [
                'actionDate' => $this->actionDate,
                'action' => $this->actionType,
            ],
        ];
    }
}
