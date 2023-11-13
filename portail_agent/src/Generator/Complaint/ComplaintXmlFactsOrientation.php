<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;

class ComplaintXmlFactsOrientation
{
    public function getOrientation(Complaint $complaint): string
    {
        $civility = $complaint->getIdentity()?->getCivilityLabel();
        $firstName = $complaint->getIdentity()?->getFirstname();
        $lastName = $complaint->getIdentity()?->getLastname();
        $witnessesText = '';
        $message = '';

        if ($complaint->getAdditionalInformation()?->isSuspectsKnown()) {
            $message .= sprintf("A la question de savoir si %s %s %s a des informations sur d'éventuels suspects, il nous déclare : %s.",
                $civility,
                $lastName,
                $firstName,
                $complaint->getAdditionalInformation()->getSuspectsKnownText()
            );
        } else {
            $message .= sprintf("%s %s %s ne peut pas nous fournir d'informations sur d'éventuels suspects. ",
                $civility,
                $lastName,
                $firstName,
            );
        }
        if ($complaint->getAdditionalInformation()?->isWitnessesPresent()) {
            $witnesses = $complaint->getAdditionalInformation()->getWitnesses()->toArray();
            $witnessesCount = count($witnesses);

            foreach ($witnesses as $witness) {
                $witnessesText .= $witness->getDescription();
                if ($witnessesCount > 1) {
                    $witnessesText .= ' ';
                }
            }

            $message .= sprintf(' Concernant la présence de témoins, %s %s %s nous signale : %s. ',
                $civility,
                $lastName,
                $firstName,
                $witnessesText
            );
        } else {
            $message .= sprintf("%s %s %s ne peut pas nous fournir d'informations sur d'éventuels suspects. ",
                $civility,
                $lastName,
                $firstName,
            );
        }
        if ($complaint->getAdditionalInformation()?->isFsiVisit() && $complaint->getAdditionalInformation()->isObservationMade()) {
            $message .= sprintf("%s %s %s nous informe de l'intervention d'un équipage de police et de la réalisation de relevés. ", $civility, $lastName, $firstName);
        } elseif ($complaint->getAdditionalInformation()?->isFsiVisit() && !$complaint->getAdditionalInformation()->isObservationMade()) {
            $message .= sprintf("%s %s %s nous informe de l'intervention d'un équipage de police sans réalisation de relevés. ", $civility, $lastName, $firstName);
        } elseif (!$complaint->getAdditionalInformation()?->isFsiVisit()) {
            $message .= sprintf("%s %s %s nous informe qu'il n'y a pas eu d'intervention d'un équipage de police. ", $civility, $lastName, $firstName);
        }

        switch ($complaint->getAdditionalInformation()?->getCctvPresent()) {
            case AdditionalInformation::CCTV_PRESENT_YES:
                if ($complaint->getAdditionalInformation()?->isCctvAvailable()) {
                    $message .= sprintf("Interrogé sur l'existence d'un enregistrement vidéo des faits, %s %s %s, nous répond par l'affirmative et déclare pouvoir la mettre à notre disposition. ", $civility, $lastName, $firstName);
                } else {
                    $message .= sprintf("Interrogé sur l'existence d'un enregistrement vidéo des faits, %s %s %s, nous répond par l'affirmative mais déclare ne pas pouvoir nous le fournir. ", $civility, $lastName, $firstName);
                }
                break;
            case AdditionalInformation::CCTV_PRESENT_NO:
                $message .= sprintf("Interrogé sur l'existence d'un enregistrement vidéo des faits, %s %s %s, nous déclare qu'il n'existe pas d'enregistrement vidéo. ", $civility, $lastName, $firstName);
                break;
        }

        $message .= sprintf("Au regard de ces faits %s %s %s dépose plainte contre X. Vu l'article 15-3-1 du code de procédure pénale, agissant conformément aux instructions de notre chef de service, recevons la plainte contre X et adressons par voie électronique à l'intéressé(e) les dispositions de l'article 10-2 du même code, les formulaires d'information des droits aux victimes et de constitution de la partie civile, le récépissé de dépôt de plainte ainsi qu'une copie du présent procès verbal. Précisons que %s %s %s sera informé(e) par le procureur de la République de la suite réservée à sa plainte que dans le cas où l'auteur des faits serait identifié. Dont acte.",
            $civility,
            $lastName,
            $firstName,
            $civility,
            $lastName,
            $firstName,
        );

        return $message;
    }
}
