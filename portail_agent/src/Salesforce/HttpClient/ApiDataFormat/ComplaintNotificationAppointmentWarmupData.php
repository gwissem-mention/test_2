<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\ApiDataFormat;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ComplaintNotificationAppointmentWarmupData implements SalesForceApiDataInterface
{
    public function __construct(
        #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
        #[SerializedName('unit_name')] public ?string $unitName,
        #[SerializedName('unit_address')] public ?string $unitAddress,
        #[SerializedName('unit_phone')] public ?string $unitPhone,
        #[SerializedName('unit_email')] public ?string $unitEmail,
        #[SerializedName('identity_appointmentTime')] public string $identityAppointmentTime,
        #[SerializedName('identity_appointmentDate')] public string $identityAppointmentDate,
        #[SerializedName('Flag_RDVAnnule')] public int $flagRDVAnnule,
        #[SerializedName('Flag_SuiteRendezVous')] public int $flagSuiteRendezVous,
    ) {
    }
}
