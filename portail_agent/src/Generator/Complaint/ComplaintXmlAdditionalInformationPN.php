<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Entity\Identity;

class ComplaintXmlAdditionalInformationPN
{
    public function set(Complaint $complaint): string
    {
        $exposedFacts = '. ';
        $exposedFacts .= $this->setIntroduction($complaint);
        $exposedFacts .= $this->setIsFranceConnected($complaint);
        $exposedFacts .= $this->setJob($complaint);

        return $exposedFacts;
    }

    private function setJob(Complaint $complaint): string
    {
        return sprintf(
            '%s %s déclare être %s.',
            $complaint->getIdentity()?->getFirstname(),
            $complaint->getIdentity()?->getLastname(),
            $complaint->getIdentity()?->getJob()
        );
    }

    private function setIntroduction(Complaint $complaint): string
    {
        return sprintf('sommes rendu destinataire de la demande de plainte en ligne, déposée sur le site internet « Plaine_En_Ligne.fr » sous le numéro d\'enregistrement : %s transmise le %s, ', $complaint->getDeclarationNumber(), $complaint->getCreatedAt()?->format('d/m/Y H:i:s'));
    }

    private function setIsFranceConnected(Complaint $complaint): string
    {
        if ($complaint->isFranceConnected()) {
            return $this->generateMessage($complaint, 'd\'un internaute s\'étant authentifié par FranceConnect sous l\'identité suivante %s %s %s, né(e) le %s à %s, %s en  %s, ');
        } else {
            return $this->generateMessage($complaint, 'd\'un internaute ne s\'étant pas authentifié par France Connect ayant déclaré l\'identité suivante %s %s %s, né(e) le %s à %s, %s en %s
                    Il a été indiqué au déclarant lors de sa déclaration qu\'en l\'absence d\'authentification par France Connect un rendez-vous en unité sera nécessaire, ');
        }
    }

    private function generateMessage(Complaint $complaint, string $message): string
    {
        return sprintf($message,
            $this->getCivility($complaint->getIdentity()?->getCivility()),
            $complaint->getIdentity()?->getFirstname(),
            $complaint->getIdentity()?->getLastname(),
            $complaint->getIdentity()?->getBirthday()?->format('d/m/Y'),
            $complaint->getIdentity()?->getBirthCity(),
            $complaint->getIdentity()?->getBirthPostalCode(),
            $complaint->getIdentity()?->getBirthCountry());
    }

    private function getCivility(?int $civility): string
    {
        return Identity::CIVILITY_MALE === $civility ? 'M' : (Identity::CIVILITY_FEMALE === $civility ? 'Mme' : '');
    }
}
