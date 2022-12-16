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
    public function load(ObjectManager $manager): void
    {
        $complaints = [
            (new Complaint())
                ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
                ->setAppointmentDate(new \DateTimeImmutable('2022-12-03'))
                ->setAssignedAgent('Jean Pierre DE FURSAC')
                ->setCommentsNumber(5)
                ->setStatus('En cours')
                ->setDeclarationNumber('PEL-2022-00000001')
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
                        ->setAddress('15 rue PAIRA, Meudon, 92190')
                        ->setAddressCity('Meudon')
                        ->setAddressPostcode('92190')
                        ->setPhone('06 12 34 45 57')
                        ->setEmail('jean.dupont@gmail.com')
                        ->setJob('Boulanger')
                )
                ->setFacts(
                    (new Facts())
                        ->setNature(Facts::NATURE_ROBBERY)
                        ->setDate(new \DateTimeImmutable('2022-12-01'))
                        ->setPlace('Restaurant')
                        ->setAddress('25 Avenue Georges Pompidou, Lyon, 69003')
                        ->setStartHour(new \DateTimeImmutable('10:00'))
                        ->setEndHour(new \DateTimeImmutable('11:00'))
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
                ),
            (new Complaint())
                ->setCreatedAt(new \DateTimeImmutable('2022-12-02'))
                ->setAppointmentDate(new \DateTimeImmutable('2022-12-07'))
                ->setAssignedAgent('George Harrison')
                ->setCommentsNumber(3)
                ->setStatus('En cours')
                ->setDeclarationNumber('PEL-2022-00000002')
                ->setOptinNotification(false)
                ->setAlert('Alert de test')
                ->setIdentity(
                    (new Identity())
                        ->setFirstname('Marine')
                        ->setLastname('VERNIER')
                        ->setCivility(Identity::CIVILITY_FEMALE)
                        ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                        ->setBirthday(new \DateTimeImmutable('1989-09-09'))
                        ->setBirthCountry('France')
                        ->setNationality('Française')
                        ->setBirthDepartment('38')
                        ->setAddress('12 Boulebard Gambetta, Grenoble, 38000')
                        ->setAddressCity('Grenoble')
                        ->setAddressPostcode('38000')
                        ->setPhone('06 35 76 66 00')
                        ->setEmail('marine.vernier@gmail.com')
                        ->setJob('Infirmière')
                )
                ->setFacts(
                    (new Facts())
                        ->setNature(Facts::NATURE_DEGRADATION)
                        ->setDate(new \DateTimeImmutable('2022-11-30'))
                        ->setPlace('Domicile')
                        ->setAddress('12 Boulebard Gambetta, Grenoble, 38000')
                        ->setStartHour(new \DateTimeImmutable('19:00'))
                        ->setEndHour(new \DateTimeImmutable('22:00'))
                )
                ->addObject(
                    (new FactsObject())
                        ->setLabel('Téléphone mobile')
                        ->setBrand('Samsung')
                        ->setModel('S22')
                        ->setOperator('SFR')
                        ->setAmount(875)
                )
                ->setAdditionalInformation(
                    (new AdditionalInformation())
                        ->setCctvPresent(AdditionalInformation::CCTV_PRESENT_NO)
                        ->setSuspectsKnown(false)
                        ->setWitnessesPresent(true)
                        ->setWitnessesPresentText('Jean DURAND')
                        ->setFsiVisit(false)
                        ->setDescription("Vol d'un SAMSUNG S22")
                ),
        ];

        foreach ($complaints as $complaint) {
            $manager->persist($complaint);
        }

        $manager->flush();
    }
}
