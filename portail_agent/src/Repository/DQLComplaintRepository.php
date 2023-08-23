<?php

declare(strict_types=1);

namespace App\Repository;

use App\Complaint\DTO\Objects\PreComplaintHistory;
use Doctrine\DBAL\Connection;

class DQLComplaintRepository
{
    private const MANDATORY_SIZE_FIELD_NUMBER = 11;

    public function __construct(private readonly Connection $ppelConnection)
    {
    }

    public function save(PreComplaintHistory $preComplaintHistory): void
    {
        $sql = 'INSERT INTO `preplainte_historique`(numero, nom, prenom, date_plainte, flag, xml, unite_mail_actif, mail_departement_actif, contact_email, prejudice_objet, date_suppression)
                VALUES (:numero, :nom, :prenom, :date_plainte, :flag, :xml, :unite_mail_actif, :mail_departement_actif, :contact_email, :prejudice_objet, :date_suppression )';
        $this->ppelConnection->prepare($sql)->executeQuery([
           'numero' => str_replace('-', '', substr($preComplaintHistory->getNumber(), -self::MANDATORY_SIZE_FIELD_NUMBER)),
           'nom' => $preComplaintHistory->getFirstName(),
           'prenom' => $preComplaintHistory->getLastName(),
           'date_plainte' => $preComplaintHistory->getComplaintDate()?->format('Y-m-d H:i:s'),
           'flag' => $preComplaintHistory->getFlag(),
           'xml' => $preComplaintHistory->getFile(),
           'unite_mail_actif' => $preComplaintHistory->getActiveUniteMail(),
           'mail_departement_actif' => $preComplaintHistory->getActiveMailDepartement(),
           'contact_email' => $preComplaintHistory->getContactEmail(),
           'prejudice_objet' => $preComplaintHistory->getPrejudiceObject(),
           'date_suppression' => $preComplaintHistory->getDeletionDate()?->format('Y-m-d H:i:s'),
        ]);
    }
}
