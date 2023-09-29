<?php

declare(strict_types=1);

namespace App\Generator\Complaint;

use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\Identity;

class ComplaintXmlAdditionalInformationPN
{
    public function set(Complaint $complaint): string
    {
        $exposedFacts = '. ';
        $exposedFacts .= $this->setIntroduction($complaint);
        $exposedFacts .= $this->setIsFranceConnected($complaint);
        $exposedFacts .= $this->setJob($complaint);
        $exposedFacts .= $this->setViolences($complaint);
        $exposedFacts .= $this->setDateAndTimeOfFacts($complaint); // set at 4th position
        $exposedFacts .= $this->setWitnesses($complaint);
        $exposedFacts .= $this->setFactsDescription($complaint); // set at 6th position
        $exposedFacts .= $this->setNatureOfPlace($complaint);
        $exposedFacts .= $this->setSimpleObjectsDegradationDescription($complaint); // set at 10th position
        $exposedFacts .= $this->setAdditionalInformation(); // Set at 11th position
        $exposedFacts .= $this->setSuspectsInformation($complaint); // Set at 12th position
        $exposedFacts .= $this->setSimpleObjectsStolen($complaint);
        $exposedFacts .= $this->setWitnesses($complaint); // set at  14th position
        $exposedFacts .= $this->setIntervention($complaint);  // Set at 15th position
        $exposedFacts .= $this->setObservationMade($complaint); // set at 16th position
        $exposedFacts .= $this->setConclusion($complaint); // set at 19th position

        return $exposedFacts;
    }

    private function setJob(Complaint $complaint): string
    {
        return sprintf(
            '%s %s déclare être %s. ',
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

    private function setViolences(Complaint $complaint): string
    {
        if ($complaint->getFacts()?->isVictimOfViolence()) {
            return sprintf(
                'La personne déclare avoir subi des violences : La victime précise sur les violences : %s. ',
                $complaint->getFacts()->getVictimOfViolenceText()
            );
        }

        return 'La personne déclare n\'avoir pas subi des violences : ';
    }

    private function setAdditionalInformation(): string
    {
        return 'Sur d\'éventuels éléments susceptibles d\'orienter l\'enquête, la victime nous précise successivement ';
    }

    private function setNatureOfPlace(Complaint $complaint): string
    {
        $message = sprintf('comme nature de lieu des faits est indiqué : %s ', $complaint->getFacts()?->getPlace());
        if (null !== $complaint->getFacts()?->getAddressAdditionalInformation()) {
            $message .= sprintf('Est apporté en précision sur le lieu des faits : %s ', $complaint->getFacts()->getAddressAdditionalInformation());
        }

        return $message;
    }

    private function setSuspectsInformation(Complaint $complaint): string
    {
        if (true === $complaint->getAdditionalInformation()?->isSuspectsKnown()) {
            return sprintf(
                'La personne déclarante indique avoir de potentielles informations sur les auteurs, à savoir : %s',
                $complaint->getAdditionalInformation()->getSuspectsKnownText()
            );
        }

        return 'La personne déclarante n\'apporte pas d\'éléments sur le ou les auteurs de l\'infraction';
    }

    private function setWitnesses(Complaint $complaint): string
    {
        $descriptions = [];
        $witnesses = $complaint->getAdditionalInformation()?->getWitnesses();

        if (null === $witnesses) {
            return "La personne déclare ne pas avoir connaissance de témoin de l'infraction";
        }

        foreach ($witnesses as $witness) {
            if ($witness->getDescription()) {
                $descriptions[] = $witness->getDescription();
            }
        }

        return sprintf('La personne déclarante déclare pouvoir nous indiquer de potentiels témoins, à savoir %s', implode(', ', $descriptions));
    }

    private function setSimpleObjectsStolen(Complaint $complaint): string
    {
        $objects = $complaint->getStolenSimpleObjects();
        if (!$objects->isEmpty()) {
            $text = ' Sont déclarés volés :';

            /** @var SimpleObject $object */
            foreach ($objects as $object) {
                $text .= sprintf(" %d %s, %s, d'une valeur estimée : %d. ", $object->getQuantity(), $object->getNature(), $object->getDescription(), $object->getAmount());
            }

            return $text;
        }

        return '';
    }

    private function setSimpleObjectsDegradationDescription(Complaint $complaint): string
    {
        $objects = $complaint->getDegradedObjects();
        if (!$objects->isEmpty()) {
            $message = ' Sont déclarés dégradés :';

            foreach ($objects as $object) {
                if ($object instanceof SimpleObject && $object->getNature()) {
                    $message .= sprintf(" %d %s, %s, %s, d'une valeur estimée : %d ", $object->getQuantity(), $object->getNature(), $object->getSerialNumber(), $object->getDescription(), $object->getAmount());
                }
            }

            return $message;
        }

        return '';
    }

    private function setObservationMade(Complaint $complaint): string
    {
        if ($complaint->getAdditionalInformation()?->isObservationMade()) {
            return 'Des relevés de traces ou indices ont été effectués.';
        }

        return '';
    }

    private function setIntervention(Complaint $complaint): string
    {
        if ($complaint->getAdditionalInformation()?->isFsiVisit()) {
            return 'Une intervention de la police ou de la gendarmerie aurait eu lieu. ';
        }

        return "Il n'y aurait pas eu d'intervention des forces de l'ordre liée aux faits ";
    }

    private function setDateAndTimeOfFacts(Complaint $complaint): string
    {
        if ($complaint->getFacts()?->isExactDateKnown() && Facts::EXACT_HOUR_KNOWN_YES === $complaint->getFacts()->getExactHourKnown()) {
            return sprintf('Interrogé sur la date et l’heure des faits, %s %s, indique que les faits se sont déroulés le %s à %s ',
                $complaint->getIdentity()?->getLastname(),
                $complaint->getIdentity()?->getFirstname(),
                $complaint->getFacts()->getStartDate()?->format('d/m/Y'),
                $complaint->getFacts()->getStartHour()?->format('h:i')
            );
        } else {
            return sprintf('Interrogé sur la date et l\'heure des faits, %s %s, indique que les faits se sont déroulés entre le %s à %s et le %s à %s ',
                $complaint->getIdentity()?->getLastname(),
                $complaint->getIdentity()?->getFirstname(),
                $complaint->getFacts()?->getStartDate()?->format('d/m/y'),
                $complaint->getFacts()?->getStartHour()?->format('h:i'),
                $complaint->getFacts()?->getEndDate()?->format('d/m/y'),
                $complaint->getFacts()?->getEndHour()?->format('h:i'),
            );
        }
    }

    private function setFactsDescription(Complaint $complaint): string
    {
        return sprintf("Sur l'exposé des faits, la personne déclarante indique : %s", $complaint->getFacts()?->getDescription());
    }

    private function setConclusion(Complaint $complaint): string
    {
        return 'Cette personne souhaite déposer plainte contre X pour les faits apportés dans sa télédéclaration ci-après annexée.';
    }
}
