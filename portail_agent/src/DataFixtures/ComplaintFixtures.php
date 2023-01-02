<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\AdditionalInformation;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObject;
use App\Entity\Identity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ComplaintFixtures extends Fixture
{
    private const COMPLAINT_NB = 25;

    public function load(ObjectManager $manager): void
    {
        $complaints = [];
        for ($i = 1; $i <= self::COMPLAINT_NB; ++$i) {
            $complaints[] = (new Complaint())
                ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
                ->setAppointmentDate(new \DateTimeImmutable('2022-12-03'))
                ->setAssignedAgent('Jean Pierre DE FURSAC')
                ->setCommentsNumber(5)
                ->setStatus(Complaint::STATUS_ONGOING)
                ->setDeclarationNumber('PEL-2022-'.str_pad((string) $i, 8, '0', STR_PAD_LEFT))
                ->setOptinNotification(true)
                ->setAlert('Alert de test trop longue')
                ->setIdentity(
                    (new Identity())
                        ->setFirstname('Jean')
                        ->setLastname('DUPONT')
                        ->setCivility(Identity::CIVILITY_MALE)
                        ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                        ->setBirthday(new \DateTimeImmutable('1967-03-07'))
                        ->setBirthCountry('France')
                        ->setNationality('Française')
                        ->setBirthDepartment('75')
                        ->setBirthCity('Paris')
                        ->setAddress('15 rue PAIRA, Meudon, 92190')
                        ->setAddressCity('Meudon')
                        ->setAddressPostcode('92190')
                        ->setPhone('06 12 34 45 57')
                        ->setEmail('jean.dupont@gmail.com')
                        ->setJob('Boulanger')
                        ->setAlertNumber(3)
                )
                ->setFacts(
                    (new Facts())
                        ->setNature(Facts::NATURE_ROBBERY)
                        ->setDate(new \DateTimeImmutable('2022-12-01'))
                        ->setPlace('Restaurant')
                        ->setAddress('25 Avenue Georges Pompidou, Lyon, 69003')
                        ->setStartHour(new \DateTimeImmutable('10:00'))
                        ->setEndHour(new \DateTimeImmutable('11:00'))
                        ->setAlertNumber(7)
                )
                ->addObject(
                    (new FactsObject())
                        ->setLabel('Téléphone mobile')
                        ->setBrand('Apple')
                        ->setModel('iPhone 13')
                        ->setOperator('Orange')
                        ->setAmount(999)
                )
                ->setAdditionalInformation(
                    (new AdditionalInformation())
                        ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_YES)
                        ->setCctvAvailable(false)
                        ->setSuspectsKnown(true)
                        ->setSuspectsKnownText('2 hommes')
                        ->setWitnessesPresent(true)
                        ->setWitnessesPresentText('Paul DUPONT')
                        ->setFsiVisit(true)
                        ->setObservationMade(true)
                        ->setDescription("Vol d'un Iphone 13")
                );
        }

        foreach ($complaints as $complaint) {
            $manager->persist($complaint);
        }

        $manager->flush();
    }
}
