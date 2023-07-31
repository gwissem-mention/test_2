<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\ApiDataFormat;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ComplaintNotificationCancellationData implements SalesForceApiDataInterface
{
    public function __construct(
        #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
        #[SerializedName('Flag_RDVAnnule')] public int $flagRDVAnnule,
        #[SerializedName('Flag_SuiteRendezVous')] public int $flagSuiteRendezVous,
    ) {
    }
}
