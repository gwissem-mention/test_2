<?php

declare(strict_types=1);

namespace App\Salesforce;

use App\Entity\Complaint;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationAppointmentDoneData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationAppointmentInitializationData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationAppointmentWarmupData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationInitializationData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationReportSentData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationUnitReassignmentData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationWarmupData;
use App\Salesforce\HttpClient\SalesForceApiEventDefinition;
use App\Salesforce\HttpClient\SalesForceHttpClientInterface;

class SalesForceComplaintNotifier
{
    public function __construct(
        private readonly SalesForceHttpClientInterface $client,
        private readonly UnitRepository $unitRepository,
        private readonly bool $ssoIsEnabled,
        private readonly string $salesForceRecipient,
        private readonly string $citoyenDomain
    ) {
    }

    public function startJourney(Complaint $complaint): void
    {
        /** @var Unit $unit */
        $unit = $this->unitRepository->findOneBy(['serviceId' => $complaint->getUnitAssigned()]);

        $eventDefinitionData = new ComplaintNotificationInitializationData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            identityFirstName: $complaint->getIdentity()?->getFirstname() ?? '',
            identityLastName: $complaint->getIdentity()?->getLastname() ?? '',
            identityEmail: $this->ssoIsEnabled ? ($complaint->getIdentity()?->getEmail() ?? '') : $this->salesForceRecipient,
            unitName: $unit->getName(),
            unitAddress: $unit->getAddress(),
            unitPhone: $unit->getPhone(),
            unitEmail: $this->ssoIsEnabled ? $unit->getEmail() : $this->salesForceRecipient,
            flagReattribution: $complaint->getReassignmentCounter() ?? 0,
            flagRendezVousObligatoire: $complaint->isAppointmentRequired(),
            complaintPersonLegalRepresentedEmail: $complaint->getPersonLegalRepresented()?->getEmail(),
            complaintCorporationContactEmail: $complaint->getCorporationRepresented()?->getContactEmail(),
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-adffc2f2-5e1d-a5a1-1027-960ed94f04a3',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }

    public function assignment(Complaint $complaint): void
    {
        $eventDefinitionData = new ComplaintNotificationWarmupData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            flagChoix: 0,
            flagReattribution: $complaint->getReassignmentCounter() ?? 0,
            flagPriseEnCompte: true,
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-54f97f47-5cf7-acb4-c4b2-ad37d29a1716',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }

    public function appointmentDone(Complaint $complaint): void
    {
        $eventDefinitionData = new ComplaintNotificationAppointmentDoneData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            flagChoix: 2,
            flagReattribution: $complaint->getReassignmentCounter() ?? 0,
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-5aa2919f-d971-d517-552d-20dca88f4a6a',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }

    public function reportSent(Complaint $complaint, int $filesCount): void
    {
        $eventDefinitionData = new ComplaintNotificationReportSentData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            lienTelechargementRecapitulatif: $this->citoyenDomain.'/mes-pv-de-plaintes',
            telechargementNombreDocuments: $filesCount,
            flagReattribution: $complaint->getReassignmentCounter() ?? 0,
            flagChoix: 1,
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-5aa2919f-d971-d517-552d-20dca88f4a6a',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }

    public function unitReassignment(Complaint $complaint): void
    {
        $eventDefinitionData = new ComplaintNotificationUnitReassignmentData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            flagChoix: 4,
            flagReattribution: $complaint->getReassignmentCounter() ?? 0,
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-5aa2919f-d971-d517-552d-20dca88f4a6a',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }

    public function appointmentInit(Complaint $complaint): void
    {
        $eventDefinitionData = new ComplaintNotificationAppointmentInitializationData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            identityFirstName: $complaint->getIdentity()?->getFirstname() ?? '',
            identityLastName: $complaint->getIdentity()?->getLastname() ?? '',
            identityEmail: $this->ssoIsEnabled ? ($complaint->getIdentity()?->getEmail() ?? '') : $this->salesForceRecipient,
            flagRDVAnnule: $complaint->getAppointmentCancellationCounter() ?? 0,
            complaintPersonLegalRepresentedEmail: $complaint->getPersonLegalRepresented()?->getEmail(),
            complaintCorporationContactEmail: $complaint->getCorporationRepresented()?->getContactEmail(),
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-68defbbf-84c7-98e2-3a89-23e0ee9ef666',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }

    public function appointmentWarmup(Complaint $complaint): void
    {
        /** @var Unit $unit */
        $unit = $this->unitRepository->findOneBy(['serviceId' => $complaint->getUnitAssigned()]);

        $eventDefinitionData = new ComplaintNotificationAppointmentWarmupData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            unitName: $unit->getName(),
            unitAddress: $unit->getAddress(),
            unitPhone: $unit->getPhone(),
            unitEmail: $this->ssoIsEnabled ? $unit->getEmail() : $this->salesForceRecipient,
            identityAppointmentTime: $complaint->getAppointmentTime()?->format('H\hi') ?? '',
            identityAppointmentDate: $complaint->getAppointmentDate()?->format('d/m/Y') ?? '',
            flagRDVAnnule: $complaint->getAppointmentCancellationCounter() ?? 0,
            flagSuiteRendezVous: 0,
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-eedbb93b-7cb4-aca3-4cff-08e4210605b8',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }
}
