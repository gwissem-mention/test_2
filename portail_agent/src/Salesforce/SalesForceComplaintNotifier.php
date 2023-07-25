<?php

declare(strict_types=1);

namespace App\Salesforce;

use App\Entity\Complaint;
use App\Salesforce\HttpClient\ApiDataFormat\ComplaintNotificationInitializationData;
use App\Salesforce\HttpClient\SalesForceApiEventDefinition;
use App\Salesforce\HttpClient\SalesForceHttpClientInterface;

class SalesForceComplaintNotifier
{
    public function __construct(private readonly SalesForceHttpClientInterface $client)
    {
    }

    public function startJourney(Complaint $complaint): void
    {
        $eventDefinitionData = new ComplaintNotificationInitializationData(
            complaintDeclarationNumber: $complaint->getDeclarationNumber(),
            identityFirstName: $complaint->getIdentity()?->getFirstname() ?? '',
            identityLastName: $complaint->getIdentity()?->getLastname() ?? '',
            identityEmail: $complaint->getIdentity()?->getEmail() ?? '',
            unitName: '', // À renseigner, vide juste pour l'exemple
            unitAddress: '', // À renseigner, vide juste pour l'exemple
            unitPhone: '', // À renseigner, vide juste pour l'exemple
            unitEmail: '', // À renseigner, vide juste pour l'exemple
            flagReattribution: 0, // À renseigner, vide juste pour l'exemple
            flagRendezVousObligatoire: true, // À renseigner, vide juste pour l'exemple
        );

        $eventDefinition = new SalesForceApiEventDefinition(
            'APIEvent-adffc2f2-5e1d-a5a1-1027-960ed94f04a3',
            $complaint->getDeclarationNumber(),
            $eventDefinitionData
        );

        $this->client->sendEvent($eventDefinition);
    }
}
