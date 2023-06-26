<?php

declare(strict_types=1);

namespace App\Logger;

final class ApplicationTracesMessage
{
    public const REJECT = 'REJET';
    public const ASSIGNMENT = 'ATTRIBUTION';
    public const VALIDATION = 'VALIDATION';
    public const APPOINTMENT_CANCELLATION_MANAGEMENT = 'ANNULATION RDV';
    public const APPOINTMENT_CHANGE_MANAGEMENT = 'MODIFICATION RDV';
    public const APPOINTMENT_VALIDATION_MANAGEMENT = 'VALIDATION RDV';
    public const REDIRECT = 'REORIONTATION';
    public const DOWNLOAD = 'TELECHARGEMENT';
    public const SENDING_DOCUMENTS = 'ENVOI DES DOCUMENTS';
    public const ADD_COMMENTS = 'AJOUT COMMENTAIRE';
    public const SELF_ASSIGNMENT = 'AUTOATTRIBUTION';

    public static function message(string $action, ?string $declarationNumber, ?string $userIdentifier, ?string $clientIp): string
    {
        return json_encode([
            'horodatage' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'IP' => $clientIp,
            'matricule' => $userIdentifier,
            'action' => $action,
            'DOSSIER' => $declarationNumber,
        ]).PHP_EOL;
    }
}
