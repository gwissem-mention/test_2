<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\ApiDataFormat;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ComplaintNotificationRejectionData implements SalesForceApiDataInterface
{
    public function __construct(
        #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
        #[SerializedName('Flag_Choix')] public int $flagChoix,
        #[SerializedName('Flag_Reattribution')] public int $flagReattribution,
        #[SerializedName('complaint_refusalReason')] public int $complaintRefusalReason,
        #[SerializedName('complaint_refusalText')] public string $complaintRefusalText,
    ) {
    }
}
