<?php

declare(strict_types=1);

namespace App\Salesforce\HttpClient\ApiDataFormat;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ComplaintNotificationReportSentData implements SalesForceApiDataInterface
{
    public function __construct(
        #[SerializedName('complaint_declarationNumber')] public string $complaintDeclarationNumber,
        #[SerializedName('LienTelechargement_Recapitulatif')] public string $lienTelechargementRecapitulatif,
        #[SerializedName('Telechargement_NombreDocuments')] public int $telechargementNombreDocuments,
        #[SerializedName('Flag_Reattribution')] public int $flagReattribution,
        #[SerializedName('Flag_Choix')] public int $flagChoix,
    ) {
    }
}
