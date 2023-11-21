<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Entity\Identity;

class ComplaintXmlFactsExpose
{
    private const TIMEZONE = 'Europe/Paris';

    public function getFactsExpose(Complaint $complaint): string
    {
        $exposedFacts = $this->setIntroduction($complaint);
        $exposedFacts .= $this->setIsFranceConnected($complaint);
        $exposedFacts .= $this->setConclusion($complaint);

        return $exposedFacts;
    }

    private function setIntroduction(Complaint $complaint): string
    {
        return sprintf("Sommes rendu destinataire de la demande de plainte en ligne, déposée sur le site internet plainte-en-ligne.masecurite.interieur.gouv.fr sous le numéro d'enregistrement %s et horodatée du %s,\n", $complaint->getDeclarationNumber(), $complaint->getCreatedAt()?->format('d/m/Y H:i:s'));
    }

    private function setIsFranceConnected(Complaint $complaint): string
    {
        $identity = $complaint->getIdentity();
        $civility = $this->getCivility($identity?->getCivility());
        $lastName = $identity?->getLastname();
        $firstName = $identity?->getFirstname();
        $birthday = $identity?->getBirthday()?->format('d/m/Y');
        $birthCity = $identity?->getBirthCity();
        $birthPostalCode = $identity?->getBirthPostalCode();
        $birthCountry = $identity?->getBirthCountry();
        $job = $identity?->getJob();
        $declarantStatus = $identity?->getDeclarantStatus();
        $corporation = $complaint->getCorporationRepresented()?->getCompanyName();

        switch (true) {
            case $complaint->isFranceConnected() && Identity::DECLARANT_STATUS_VICTIM === $declarantStatus:
                $isFcMessage = "d'un internaute s'étant authentifié par FranceConnect sous l'identité suivante %s %s %s, né(e) le %s à %s, %s en %s\n et qui déclare exercer l'activité de %s.\n";

                return sprintf($isFcMessage, $civility, $lastName, $firstName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job);
            case $complaint->isFranceConnected() && Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $declarantStatus:
                $isFcMessage = "d'un internaute s'étant authentifié par FranceConnect sous l'identité suivante %s %s %s, né(e) le %s, à %s %s, en %s et qui déclare exercer l'activité de %s, agissant pour le compte de %s.\n";

                return sprintf($isFcMessage, $civility, $lastName, $firstName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job, $corporation);
            case !$complaint->isFranceConnected() && Identity::DECLARANT_STATUS_VICTIM === $declarantStatus:
                $isFcMessage = "d'un internaute ayant déclaré l'identité suivante %s %s %s, né(e) le %s, à %s %s, en %s et qui déclare exercer l'activité de %s.\n";

                return sprintf($isFcMessage, $civility, $lastName, $firstName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job);
            case !$complaint->isFranceConnected() && Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $declarantStatus:
                $isFcMessage = "d'un internaute ayant déclaré l'identité suivante %s %s %s, né(e) le %s, à %s %s, en %s et qui déclare exercer l'activité de %s, agissant pour le compte de %s.\n";

                return sprintf($isFcMessage, $civility, $lastName, $firstName, $birthday, $birthCity, $birthPostalCode, $birthCountry, $job, $corporation);
        }

        return '';
    }

    private function getCivility(?int $civility): string
    {
        return Identity::CIVILITY_MALE === $civility ? 'M' : (Identity::CIVILITY_FEMALE === $civility ? 'Mme' : '');
    }

    private function setConclusion(Complaint $complaint): string
    {
        return sprintf("Interrogé sur la date et l'heure des faits, %s %s, indique que les faits se sont déroulés %s. Sur l'exposé des faits, la personne déclarante indique :",
            $complaint->getIdentity()?->getLastname(),
            $complaint->getIdentity()?->getFirstname(),
            $this->getTimeInterval($complaint),
        );
    }

    private function getTimeInterval(Complaint $complaint): string
    {
        $start = '';
        $end = '';
        $debut = $complaint->getFacts()?->getStartDate();
        $fin = $complaint->getFacts()?->getEndDate();
        $startHour = $complaint->getFacts()?->getStartHour() ? \DateTime::createFromInterface($complaint->getFacts()->getStartHour()) : null;
        $endHour = $complaint->getFacts()?->getEndHour() ? \DateTime::createFromInterface($complaint->getFacts()->getEndHour()) : null;

        $startHour?->setDate((int) $debut?->format('Y'), (int) $debut?->format('m'), (int) $debut?->format('d'))->setTimezone(new \DateTimeZone(self::TIMEZONE));
        $endHour?->setDate((int) $debut?->format('Y'), (int) $debut?->format('m'), (int) $debut?->format('d'))->setTimezone(new \DateTimeZone(self::TIMEZONE));

        if (null !== $debut && null !== $fin && null !== $startHour && null !== $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $finFormatted = $fin->format('d/m/Y');
            $start = $debutFormatted.' à '.$startHour->format('H:i');
            $end = $finFormatted.' à '.$endHour->format('H:i');
        } elseif (null !== $debut && null === $startHour && null === $fin && null === $endHour) {
            $start = $debut->format('d/m/Y').' à 00h00';
            $end = $debut->format('d/m/Y').' à 23h59';
        } elseif (null !== $debut && null !== $startHour && null === $fin && null !== $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $startHour = \DateTime::createFromInterface($startHour);
            $endHour = \DateTime::createFromInterface($endHour);
            $start = $debutFormatted.' à '.$startHour->format('H:i');
            $end = $debutFormatted.' à '.$endHour->format('H:i');
        } elseif (null !== $debut && null !== $startHour && null === $fin && null === $endHour) {
            $debutFormatted = $debut->format('d/m/Y');
            $startHour = \DateTime::createFromInterface($startHour);
            $endHour = clone $startHour;
            $endHour->modify('+5 minutes');
            $start = $debutFormatted.' à '.$startHour->format('H:i');
            $end = $debutFormatted.' à '.$endHour->format('H:i');
        }

        return sprintf('entre le %s et le %s', $start, $end);
    }
}
