<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\ApiDataFormat;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ComplaintNotificationAppointmentInitializationData implements SalesForceApiDataInterface
{
    public function __construct(
        #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
        #[SerializedName('identity_firstName')] public string $identityFirstName,
        #[SerializedName('identity_lastName')] public string $identityLastName,
        #[SerializedName('identity_email')] public string $identityEmail,
        #[SerializedName('Flag_RDVAnnule')] public int $flagRDVAnnule,
        #[SerializedName('complaint_personLegalRepresented_email')] public ?string $complaintPersonLegalRepresentedEmail = null,
        #[SerializedName('complaint_corporation_contactEmail')] public ?string $complaintCorporationContactEmail = null,
    ) {
    }
}
