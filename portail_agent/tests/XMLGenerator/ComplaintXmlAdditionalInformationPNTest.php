<?php

declare(strict_types=1);

namespace App\Tests\XMLGenerator;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\AbstractObject;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\Identity;
use App\Entity\Witness;
use App\Generator\Complaint\ComplaintXmlAdditionalInformationPN;
use PHPUnit\Framework\TestCase;

class ComplaintXmlAdditionalInformationPNTest extends TestCase
{
    public function testSet(): void
    {
        $complaint = new Complaint();

        $identity = new Identity();
        $identity->setCivility(Identity::CIVILITY_MALE)
        ->setFirstname('John')
        ->setLastname('Doe')
        ->setJob('Developer')
        ->setBirthday(new \DateTimeImmutable('1990-08-08'))
        ->setBirthCity('Avignon')
        ->setBirthPostalCode('84000')
        ->setBirthCountry('France');

        $complaint->setIdentity($identity)

       ->setDeclarationNumber('PEL-2023-00000001')
        ->setCreatedAt(new \DateTimeImmutable('2023-10-04 15:44:32'))
        ->setFranceConnected(true)
        ->setAdditionalInformation(
            (new AdditionalInformation())
                ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_YES)
                ->setCctvAvailable(true)
                ->setSuspectsKnown(true)
                ->setSuspectsKnownText('2 hommes')
                ->setWitnessesPresent(true)
                ->setFsiVisit(true)
                ->setObservationMade(true)
                ->addWitness(
                    (new Witness())
                        ->setDescription('Jean Dupont')
                        ->setPhone('06 12 34 45 57')
                        ->setEmail('jean@example.com')
                )
        )
            ->addObject(
                (new SimpleObject())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setNature('Blouson')
                    ->setDescription('Blouson bleu')
                    ->setSerialNumber('1234567890')
                    ->setQuantity(1)
                    ->setAmount(100)
            )
            ->setFacts(
                (new Facts())
                    ->setNatures([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])
                    ->setDescription('Je me suis fait voler mon portable.')
                    ->setExactDateKnown(true)
                    ->setExactPlaceUnknown(true)
                    ->setStartDate(new \DateTimeImmutable('2022-12-01'))
                    ->setEndDate(new \DateTimeImmutable('2022-12-01'))
                    ->setPlace('TRAIN')
                    ->setExactHourKnown(Facts::EXACT_HOUR_KNOWN_NO)
                    ->setStartHour(new \DateTimeImmutable('09:00'))
                    ->setEndHour(new \DateTimeImmutable('10:00'))
                    ->setAddressAdditionalInformation(
                        "Les faits se sont produits entre le restaurant et l'appartement d'un ami"
                    )
                    ->setVictimOfViolence(true)
                    ->setVictimOfViolenceText('Je me suis fait taper')
            );

        $xmlAdditionalInformationPN = new ComplaintXmlAdditionalInformationPN();

        $output = $xmlAdditionalInformationPN->set($complaint);

        $expectedOutput = <<<EOT
         sommes rendu destinataire de la demande de plainte en ligne, déposée sur le site internet « Plaine_En_Ligne.fr » sous le numéro d'enregistrement : PEL-2023-00000001 transmise le 04/10/2023 15:44:32,
        d'un internaute s'étant authentifié par FranceConnect sous l'identité suivante M John Doe, né(e) le 08/08/1990 à Avignon, 84000 en  France.
        John Doe déclare être Developer.
        La personne déclare avoir subi des violences : La victime précise sur les violences : Je me suis fait taper.
        Interrogé sur la date et l'heure des faits, Doe John, indique que les faits se sont déroulés entre le 01/12/22 à 09:00 et le 01/12/22 à 10:00.
        comme nature de lieu des faits est indiqué : TRAIN.
        Est apporté en précision sur le lieu des faits : Les faits se sont produits entre le restaurant et l'appartement d'un ami.
        Sur l'exposé des faits, la personne déclarante indique : Je me suis fait voler mon portable.  Sont déclarés volés : 1 Blouson, Blouson bleu, d'une valeur estimée : 100.
        Sur d'éventuels éléments susceptibles d'orienter l'enquête, la victime nous précise successivement
        La personne déclarante indique avoir de potentielles informations sur les auteurs, à savoir : 2 hommes
        La personne déclarante déclare pouvoir nous indiquer de potentiels témoins, à savoir Jean Dupont.
        Une intervention de la police ou de la gendarmerie aurait eu lieu.
        Des relevés de traces ou indices ont été effectués.
        La personne déclarante indique : qu'une vidéo des faits existerait et être en mesure de fournir le support vidéo
         Cette personne souhaite déposer plainte contre X pour les faits apportés dans sa télédéclaration ci-après annexée.
        EOT;

        $this->assertSame($expectedOutput, $output);
    }
}
