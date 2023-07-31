<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\ApiDataFormat;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ComplaintNotificationInitializationData implements SalesForceApiDataInterface
{
    public function __construct(
        #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
        #[SerializedName('identity_firstName')] public string $identityFirstName,
        #[SerializedName('identity_lastName')] public string $identityLastName,
        #[SerializedName('identity_email')] public string $identityEmail,
        #[SerializedName('unit_name')] public ?string $unitName,
        #[SerializedName('unit_address')] public ?string $unitAddress,
        #[SerializedName('unit_phone')] public ?string $unitPhone,
        #[SerializedName('unit_email')] public ?string $unitEmail,
        #[SerializedName('Flag_Reattribution')] public int $flagReattribution,
        #[SerializedName('Flag_RendezVousObligatoire')] public ?bool $flagRendezVousObligatoire,
        #[SerializedName('complaint_personLegalRepresented_email')] public ?string $complaintPersonLegalRepresentedEmail = null,
        #[SerializedName('complaint_corporation_contactEmail')] public ?string $complaintCorporationContactEmail = null,
    ) {
    }
}
