<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Entity\Identity;

class ComplaintXmlAdditionalInformationPN
{
    public function set(Complaint $complaint): string
    {
        $exposedFacts = ' ';
        $exposedFacts .= $this->setIntroduction($complaint);
        $exposedFacts .= $this->setIsFranceConnected($complaint);
        $exposedFacts .= $this->setConclusion($complaint);

        return $exposedFacts;
    }

    private function setIntroduction(Complaint $complaint): string
    {
        return sprintf("Sommes rendu destinataire de la demande de plainte en ligne, déposée sur le site internet plainte-en-ligne.masecurite.interieur.gouv.fr sous le numéro d’enregistrement %s et horodatée du %s,\n", $complaint->getDeclarationNumber(), $complaint->getCreatedAt()?->format('d/m/Y H:i:s'));
    }

    private function setIsFranceConnected(Complaint $complaint): string
    {
        $identity = $complaint->getIdentity();
        $civility = $this->getCivility($identity?->getCivility());
        $firstName = $identity?->getFirstname();
        $lastName = $identity?->getLastname();
        $birthday = $identity?->getBirthday()?->format('d/m/Y');
        $birthCity = $identity?->getBirthCity();
        $birthPostalCode = $identity?->getBirthPostalCode();
        $birthCountry = $identity?->getBirthCountry();
        $job = $identity?->getJob();
        $declarantStatus = $identity?->getDeclarantStatus();
        $corporation = $complaint->getCorporationRepresented()?->getCompanyName();

        switch (true) {
            case $complaint->isFranceConnected() && Identity::DECLARANT_STATUS_VICTIM === $declarantStatus:
                $isFcMessage = "d’un internaute s’étant authentifié par FranceConnect sous l’identité suivante %s %s %s, né(e) le %s à %s, %s en  %s.\n et qui déclare exercer l’activité de %s\n";

                return sprintf($isFcMessage, $civility, $firstName, $lastName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job);
            case $complaint->isFranceConnected() && Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $declarantStatus:
                $isFcMessage = "d’un internaute s’étant authentifié par FranceConnect sous l’identité suivante %s %s %s, né(e) le %s, à %s %s, en %s et qui déclare exercer l’activité de %s, agissant  pour le compte de %s.\n";

                return sprintf($isFcMessage, $civility, $firstName, $lastName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job, $corporation);
            case !$complaint->isFranceConnected() && Identity::DECLARANT_STATUS_VICTIM === $declarantStatus:
                $isFcMessage = "d'un internaute ayant déclaré l’identité suivante %s %s %s, né(e) le %s, à %s %s, en %s et qui déclare exercer l’activité de %s.\n";

                return sprintf($isFcMessage, $civility, $firstName, $lastName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job);
            case !$complaint->isFranceConnected() && Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $declarantStatus:
                $isFcMessage = "d'un internaute ayant déclaré l’identité suivante %s %s %s, né(e) le %s, à %s %s, en %s. et qui déclare exercer l’activité de %s, agissant  pour le compte de %s.\n";

                return sprintf($isFcMessage, $civility, $firstName, $lastName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job, $corporation);
        }

        return '';
    }

    private function getCivility(?int $civility): string
    {
        return Identity::CIVILITY_MALE === $civility ? 'M' : (Identity::CIVILITY_FEMALE === $civility ? 'Mme' : '');
    }

    private function setConclusion(Complaint $complaint): string
    {
        return sprintf("Interrogé sur la date et l’heure des faits, %s %s, indique que les faits se sont déroulés  entre le %s et le %s. Sur l'exposé des faits, la personne déclarante indique :",
            $complaint->getIdentity()?->getFirstname(),
            $complaint->getIdentity()?->getLastname(),
            $complaint->getFacts()?->getStartDate()?->format('d/m/Y'),
            $complaint->getFacts()?->getEndDate()?->format('d/m/Y')
        );
    }
}
