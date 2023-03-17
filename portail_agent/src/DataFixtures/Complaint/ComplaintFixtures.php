<?php

declare(strict_types=1);

namespace App\DataFixtures\Complaint;

use App\DataFixtures\UserFixtures;
use App\Entity\AdditionalInformation;
use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use App\Entity\Identity;
use App\Entity\User;
use App\Factory\NotificationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ComplaintFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const COMPLAINTS_NB = 10;

    public function __construct(private readonly NotificationFactory $notificationFactory)
    {
    }

    public static function getGroups(): array
    {
        return ['ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $complaints = [];
        /** @var User $userUnit1 */
        $userUnit1 = $manager->getRepository(User::class)->findOneBy(['number' => 'H3U3XCGD']);
        /** @var User $userUnit2 */
        $userUnit2 = $manager->getRepository(User::class)->findOneBy(['number' => 'PR5KTQSD']);
        $unitsUsers = [
            '103131' => $userUnit1,
            '3002739' => $userUnit2,
        ];

        foreach ($unitsUsers as $unit => $user) {
            $unit = strval($unit);
            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaint = $this
                    ->getGenericComplaint()
                    ->setUnitAssigned($unit);
                /** @var Identity $identity */
                $identity = $complaint->getIdentity();
                $complaints[] = $complaint
                    ->setIdentity($identity
                        ->setFirstname('Léo')
                        ->setLastname('BERNARD'))
                    ->setStatus(Complaint::STATUS_ASSIGNED)
                    ->setUnitAssigned($unit)
                    ->setAssignedTo($user);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setStatus(Complaint::STATUS_ONGOING_LRP)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-02'))
                    ->setUnitAssigned($unit)
                    ->setAssignedTo($user);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setStatus(Complaint::STATUS_REJECTED)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-03'))
                    ->setRefusalReason(Complaint::REFUSAL_REASON_REORIENTATION_APPONTMENT)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-04'))
                    ->setStatus(Complaint::STATUS_MP_DECLARANT)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_CLOSED)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_APPOINTMENT_PENDING)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_REASSIGNMENT_PENDING)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint()
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_UNIT_REASSIGNMENT_PENDING)
                    ->setUnitAssigned($unit);
            }
        }

        foreach ($complaints as $complaint) {
            $manager->persist($complaint);
        }
        $manager->flush();
        $manager->clear();

        /** @var User $user */
        $user = $manager->getRepository(User::class)->findOneBy([]);

        /** @var array<Complaint> $complaints */
        $complaints = $manager->getRepository(Complaint::class)->findBy(['id' => [11, 12]]);

        foreach ($complaints as $complaint) {
            $manager->persist(
                $user->addNotification($this->notificationFactory->createForComplaintAssigned($complaint))
            );
        }
        $manager->flush();
        $manager->clear();
    }

    private function getGenericComplaint(): Complaint
    {
        return (new Complaint())
            ->setTest(true)
            ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
            ->setAppointmentDate(new \DateTimeImmutable('2022-12-03'))
            ->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING)
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
                    ->setBirthDepartment('Paris')
                    ->setBirthDepartmentNumber(75)
                    ->setBirthCity('Paris')
                    ->setBirthPostalCode('75000')
                    ->setBirthInseeCode('75056')
                    ->setAddress('15 rue PAIRA, Meudon, 92190')
                    ->setAddressStreetNumber('15')
                    ->setAddressStreetType('Rue')
                    ->setAddressStreetName('PAIRA')
                    ->setAddressCity('Meudon')
                    ->setAddressInseeCode('92048')
                    ->setAddressPostcode('92190')
                    ->setAddressDepartment('Hauts-de-Seine')
                    ->setAddressDepartmentNumber(92)
                    ->setAddressCountry('France')
                    ->setMobilePhone('06 12 34 45 57')
                    ->setEmail('jean.dupont@gmail.com')
                    ->setJob('Boulanger')
                    ->setAlertNumber(3)
            )
            ->setFacts(
                (new Facts())
                    ->setNatures([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])
                    ->setDescription('Le vol à eu lieu à mon domicile')
                    ->setExactDateKnown(true)
                    ->setStartDate(new \DateTimeImmutable('2022-12-01'))
                    ->setExactPlaceUnknown(false)
                    ->setPlace('Restaurant')
                    ->setStartAddress('25 Avenue Georges Pompidou, Lyon, 69003')
                    ->setEndAddress('Place Charles Hernu, Villeurbanne, 69100')
                    ->setCity('Lyon')
                    ->setPostalCode('69003')
                    ->setInseeCode('69123')
                    ->setDepartment('Rhône')
                    ->setDepartmentNumber(69)
                    ->setCountry('France')
                    ->setExactHourKnown(Facts::EXACT_HOUR_KNOWN_NO)
                    ->setStartHour(new \DateTimeImmutable('09:00'))
                    ->setEndHour(new \DateTimeImmutable('10:00'))
                    ->setAlertNumber(7)
                    ->setAddressAdditionalInformation(
                        "Les faits se sont produits entre le restaurant et l'appartement d'un ami"
                    )
            )
            ->addObject(
                (new MultimediaObject())
                    ->setLabel('Téléphone mobile')
                    ->setBrand('Apple')
                    ->setModel('iPhone 13')
                    ->setOperator('Orange')
                    ->setSerialNumber(1234567890)
                    ->setPhoneNumber('06 12 34 56 67')
                    ->setAmount(999)
            )
            ->addObject(
                (new AdministrativeDocument())
                    ->setType('Permis de conduire')
            )
            ->addObject(
                (new PaymentMethod())
                    ->setDescription('Visa principale')
                    ->setType('Carte Bancaire VISA')
                    ->setBank('LCL')
            )
            ->addObject(
                (new Vehicle())
                    ->setLabel('Voiture')
                    ->setBrand('Citroën')
                    ->setModel('C3')
                    ->setRegistrationNumber('AA-123-AA')
                    ->setRegistrationCountry('France')
                    ->setAmount(17000)
            )
            ->addObject(
                (new SimpleObject())
                    ->setNature('Blouson')
                    ->setDescription('Blouson Adidas de couleur bleu')
                    ->setAmount(100)
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
                    ->setVictimOfViolence(true)
                    ->setDescription("Vol d'un Iphone 13")
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est un commentaire.')
                    ->setAuthor(Comment::AGENT_AUTHOR)
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est un autre commentaire.')
                    ->setAuthor(Comment::ANOTHER_AGENT_AUTHOR)
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est (encore) un autre commentaire.')
                    ->setAuthor(Comment::ANOTHER_AGENT_AUTHOR)
            )->addComment(
                (new Comment())
                    ->setContent('Commentaire.')
                    ->setAuthor(Comment::ANOTHER_AGENT_AUTHOR)
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est un commentaire de moi.')
                    ->setAuthor(Comment::AGENT_AUTHOR)
            );
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
