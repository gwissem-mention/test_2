<?php

declare(strict_types=1);

namespace App\Salesforce;

use App\Entity\Complaint;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationInitializationData;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationWarmupData;
use App\Salesforce\HttpClient\SalesForceApiEventDefinition;
use App\Salesforce\HttpClient\SalesForceHttpClientInterface;

class SalesForceComplaintNotifier
{
    public function __construct(
        private readonly SalesForceHttpClientInterface $client,
        private readonly UnitRepository $unitRepository,
        private readonly bool $ssoIsEnabled,
        private readonly string $salesForceRecipient
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
}
