<?php

declare(strict_types=1);

namespace App\DataFixtures\Complaint;

use App\DataFixtures\UserFixtures;
use App\Entity\AdditionalInformation;
use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\Corporation;
use App\Entity\Facts;
use App\Entity\FactsObjects\AbstractObject;
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
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpKernel\KernelInterface;

class ComplaintFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const COMPLAINTS_NB = 10;
    private const REFUSAL_REASON_REORIENTATION_OTHER_SOLUTION = 'reorientation-other-solution';

    public function __construct(private readonly NotificationFactory $notificationFactory,
        private readonly FilesystemOperator $defaultStorage,
        private readonly KernelInterface $kernel,
        private readonly string $tmpComplaintFolderId,
    ) {
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
        $users = [$userUnit1, $userUnit2];
        $unitsUsers = [
            '103131' => $userUnit1,
            '3002739' => $userUnit2,
        ];
        $this->defaultStorage->writeStream('/blank.pdf', fopen($this->kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'rb'));
        $this->defaultStorage->writeStream('/iphone.png', fopen($this->kernel->getProjectDir().'/tests/Behat/Files/iphone.png', 'rb'));

        foreach ($unitsUsers as $unit => $user) {
            $unit = strval($unit);
            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint($users)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaint = $this
                    ->getGenericComplaint($users)
                    ->setUnitAssigned($unit);
                /** @var Identity $identity */
                $identity = $complaint->getIdentity();
                $complaints[] = $complaint
                    ->setIdentity($identity
                        ->setFirstname('Léo')
                        ->setLastname('BERNARD')
                        ->setFamilySituation('Marié(e)')
                        ->setHomePhone('01 23 45 67 89'))
                    ->setStatus(Complaint::STATUS_ASSIGNED)
                    ->setAppointmentDate(new \DateTimeImmutable('2022-12-01'))
                    ->setAppointmentTime(new \DateTimeImmutable('2022-12-01'))
                    ->setUnitAssigned($unit)
                    ->setAssignedTo($user);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint($users)
                    ->setStatus(Complaint::STATUS_ONGOING_LRP)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-02'))
                    ->setUnitAssigned($unit)
                    ->setAssignedTo($user);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint($users)
                    ->setStatus(Complaint::STATUS_REJECTED)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-03'))
                    ->setRefusalReason(self::REFUSAL_REASON_REORIENTATION_OTHER_SOLUTION)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint($users)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-04'))
                    ->setStatus(Complaint::STATUS_MP_DECLARANT)
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint($users)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_CLOSED)
                    ->setClosedAt((new \DateTimeImmutable())->sub(new \DateInterval('P200D')))
                    ->setUnitAssigned($unit);
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaint = $this
                    ->getGenericComplaint($users)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_APPOINTMENT_PENDING)
                    ->setUnitAssigned($unit);
                /** @var Identity $identity */
                $identity = $complaint->getIdentity();
                $complaints[] = $complaint
                    ->setIdentity($identity
                        ->setDeclarantStatus(2)
                        ->setFamilySituation('Concubinage'))
                    ->setpersonLegalRepresented(
                        (new Identity())
                            ->setFirstname('Jeremy')
                            ->setLastname('DUPONT')
                            ->setCivility(Identity::CIVILITY_MALE)
                            ->setFamilySituation('Célibataire')
                            ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                            ->setBirthday(new \DateTimeImmutable('2000-02-14'))
                            ->setBirthCountry('France')
                            ->setNationality('FRANCAISE')
                            ->setBirthDepartment('Hauts-de-Seine')
                            ->setBirthDepartmentNumber(92)
                            ->setBirthCity('Meudon')
                            ->setBirthPostalCode('92190')
                            ->setBirthInseeCode('92048')
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
                            ->setMobilePhone('06 76 54 32 10')
                            ->setEmail('jeremy.dupont@gmail.com')
                            ->setJob('Etudiant')
                    )
                    ->addObject(
                        (new AdministrativeDocument())
                            ->setStatus(AbstractObject::STATUS_STOLEN)
                            ->setType('Passeport')
                            ->setOwned(false)
                            ->setOwnerLastname('Dulac')
                            ->setOwnerFirstname('Raymond')
                            ->setOwnerCompany('Amazon')
                            ->setOwnerPhone('0612345678')
                            ->setOwnerEmail('raymond.dulac@example.fr')
                            ->setOwnerAddress('100 Rue de l\'église 69000 Lyon')
                            ->setNumber('123')
                            ->setIssuedBy('Préfecture de Lyon')
                            ->setIssuedOn(new \DateTimeImmutable('2022-03-10'))
                            ->setValidityEndDate(new \DateTimeImmutable('2025-12-05'))
                            ->setOwnerAddressStreetType('Rue')
                            ->setOwnerAddressStreetNumber('100')
                            ->setOwnerAddressStreetName('de l\'église')
                            ->setOwnerAddressInseeCode('69123')
                            ->setOwnerAddressPostcode('69000')
                            ->setOwnerAddressCity('Lyon')
                            ->setOwnerAddressDepartmentNumber('69')
                            ->setOwnerAddressDepartment('Rhône')
                    );
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaint = $this->getGenericComplaint($users)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_ASSIGNED)
                    ->setUnitAssigned($unit);
                /** @var Identity $identity */
                $identity = $complaint->getIdentity();
                $complaints[] = $complaint
                    ->setIdentity($identity
                        ->setDeclarantStatus(3)
                        ->setFamilySituation('Divorcé(e)'))
                    ->setCorporationRepresented(
                        (new Corporation())
                            ->setSirenNumber('123456789')
                            ->setCompanyName('Netflix')
                            ->setDeclarantPosition('PDG')
                            ->setNationality('FRANCAISE')
                            ->setContactEmail('pdg@netflix.com')
                            ->setPhone('0612345678')
                            ->setCountry('France')
                            ->setDepartment('Paris')
                            ->setDepartmentNumber(75)
                            ->setCity('Paris')
                            ->setPostCode('75000')
                            ->setInseeCode('75056')
                            ->setStreetNumber(1)
                            ->setStreetName('Rue de la république')
                            ->setStreetType('Rue')
                            ->setAddress('1 Rue de la république, Paris, 75000')
                    );
            }

            for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
                $complaints[] = $this->getGenericComplaint($users)
                    ->setCreatedAt(new \DateTimeImmutable('2022-12-05'))
                    ->setStatus(Complaint::STATUS_UNIT_REDIRECTION_PENDING)
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

    /** @param User[] $users */
    private function getGenericComplaint(array $users): Complaint
    {
        return (new Complaint())
            ->setTest(true)
            ->setOodriveFolder($this->tmpComplaintFolderId)
            ->setCreatedAt(new \DateTimeImmutable('2022-12-01'))
            ->setAppointmentContactInformation('Disponible entre 10h et 12h le lundi')
            ->setStatus(Complaint::STATUS_ASSIGNMENT_PENDING)
            ->setOptinNotification(true)
            ->setAlert('Alert de test trop longue')
            ->setIdentity(
                (new Identity())
                    ->setFirstname('Jean')
                    ->setLastname('DUPONT')
                    ->setCivility(Identity::CIVILITY_MALE)
                    ->setFamilySituation('Célibataire')
                    ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                    ->setBirthday(new \DateTimeImmutable('1967-03-07'))
                    ->setBirthCountry('France')
                    ->setNationality('FRANCAISE')
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
                    ->setVictimOfViolence(true)
                    ->setVictimOfViolenceText("J'ai été victime de violences")
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
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setLabel('Téléphone mobile')
                    ->setBrand('Apple')
                    ->setModel('iPhone 13')
                    ->setOperator('Orange')
                    ->setSerialNumber('1234567890')
                    ->setPhoneNumber('06 12 34 56 67')
                    ->setStillOnWhenMobileStolen(true)
                    ->setKeyboardLockedWhenMobileStolen(false)
                    ->setPinEnabledWhenMobileStolen(true)
                    ->setMobileInsured(false)
                    ->setAmount(999)
                    ->setFiles(['blank.pdf', 'iphone.png'])
            )
            ->addObject(
                (new AdministrativeDocument())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
                    ->setType('Permis de conduire')
                    ->setOwned(true)
            )
            ->addObject(
                (new PaymentMethod())
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
                    ->setDescription('Visa principale')
                    ->setType('Carte Bancaire VISA')
                    ->setBank('LCL')
            )
            ->addObject(
                (new Vehicle())
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
                    ->setLabel('Voiture')
                    ->setBrand('Citroën')
                    ->setModel('C3')
                    ->setRegistrationNumber('AA-123-AA')
                    ->setRegistrationCountry('France')
                    ->setAmount(17000)
            )
            ->addObject(
                (new SimpleObject())
                    ->setStatus(AbstractObject::STATUS_STOLEN)
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
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est un commentaire.')
                    ->setAuthor($users[0])
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est un autre commentaire.')
                    ->setAuthor($users[1])
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est (encore) un autre commentaire.')
                    ->setAuthor($users[1])
            )->addComment(
                (new Comment())
                    ->setContent('Commentaire.')
                    ->setAuthor($users[1])
            )
            ->addComment(
                (new Comment())
                    ->setContent('Ceci est un commentaire différent.')
                    ->setAuthor($users[0])
            );
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
