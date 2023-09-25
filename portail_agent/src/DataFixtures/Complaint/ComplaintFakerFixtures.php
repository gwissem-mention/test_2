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
use App\Entity\Witness;
use App\Factory\NotificationFactory;
use App\Notification\ComplaintNotification;
use App\Repository\ComplaintRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpKernel\KernelInterface;

class ComplaintFakerFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const COMPLAINTS_NB = 70;

    /** @var string[] */
    private array $places = [];
    /** @var string[] */
    private array $departments = [];
    /** @var string[] */
    private array $insees = [];
    /** @var User[] */
    private array $users = [];
    private mixed $identityGender;
    private string $identityBirthPostcode;
    private string $identityAddressStreetAddress;
    private string $identityAddressCity;
    private string $identityAddressPostcode;

    public function __construct(
        private readonly Generator $faker,
        private readonly NotificationFactory $notificationFactory,
        private readonly ComplaintNotification $complaintNotification,
        private readonly FilesystemOperator $defaultStorage,
        private readonly KernelInterface $kernel,
        private readonly string $tmpComplaintFolderId,
    ) {
        $faker->seed(1);
    }

    public static function getGroups(): array
    {
        return ['default'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->places = [
            '06000' => 'Nice',
            '13000' => 'Marseille',
            '75000' => 'Paris',
            '69000' => 'Lyon',
            '33000' => 'Bordeaux',
            '38000' => 'Grenoble',
        ];

        $this->insees = [
            '06000' => '06088',
            '13000' => '13055',
            '75000' => '75056',
            '69000' => '69123',
            '33000' => '33063',
            '38000' => '38185',
        ];

        $this->departments = [
            '06' => 'Alpes-Maritimes',
            '13' => 'Bouches-du-Rhône',
            '75' => 'Paris',
            '69' => 'Rhône',
            '33' => 'Gironde',
            '38' => 'Isère',
        ];

        $this->users = $manager->getRepository(User::class)->findAll();
        $this->defaultStorage->writeStream('/blank.pdf', fopen($this->kernel->getProjectDir().'/tests/Behat/Files/blank.pdf', 'rb'));
        $this->defaultStorage->writeStream('/iphone.png', fopen($this->kernel->getProjectDir().'/tests/Behat/Files/iphone.png', 'rb'));

        for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
            $factsStartDate = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('2023-01-01', '2023-01-31'));
            $complaintDate = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('2023-02-01', '2023-02-27'));
            $factsStartHour = \DateTimeImmutable::createFromMutable($this->faker->dateTime('2023-01-31 10:00:00'));

            $this->identityGender = $this->faker->randomElement(['male', 'female']);

            /** @var string $status */
            $status = $this->faker->randomElement([
                Complaint::STATUS_APPOINTMENT_PENDING,
                Complaint::STATUS_ASSIGNMENT_PENDING,
                Complaint::STATUS_ASSIGNED,
                Complaint::STATUS_CLOSED,
                Complaint::STATUS_REJECTED,
                Complaint::STATUS_ONGOING_LRP,
                Complaint::STATUS_MP_DECLARANT,
                Complaint::STATUS_UNIT_REDIRECTION_PENDING,
            ]);
            $this->identityBirthPostcode = (string) $this->faker->randomKey($this->places);
            $this->identityAddressStreetAddress = $this->faker->streetAddress;
            $this->identityAddressPostcode = (string) $this->faker->randomKey($this->places);
            $this->identityAddressCity = $this->places[$this->identityAddressPostcode];
            $factsAddressPostcode = $this->faker->randomKey($this->places);
            $factsAddressCity = $this->places[$factsAddressPostcode];

            /** @var string $unit */
            $unit = $this->faker->randomElement(['103131', '3002739']);
            $exactDateKnown = $this->faker->boolean;
            /** @var int $exactHourKnown */
            $exactHourKnown = $this->faker->randomElement([Facts::EXACT_HOUR_KNOWN_NO, Facts::EXACT_HOUR_KNOWN_YES]);

            /** @var int $cctvPresent */
            $cctvPresent = $this->faker->randomElement([AdditionalInformation::CCTV_PRESENT_YES, AdditionalInformation::CCTV_PRESENT_NO, AdditionalInformation::CCTV_PRESENT_DONT_KNOW]);
            $suspectsKnown = $this->faker->boolean;
            $witnessesPresent = $this->faker->boolean;
            $fsiVisit = $this->faker->boolean;
            $victimOfViolence = $this->faker->boolean;

            if ('103131' === $unit) {
                $agentNumber = $this->faker->randomElement(['H3U3XCGD', 'H3U3XCGF']);
            } else {
                $agentNumber = $this->faker->randomElement(['PR5KTZ9R', 'PR5KTQSD']);
            }

            $complaint = (new Complaint())
                ->setTest(true)
                ->setOodriveFolder($this->tmpComplaintFolderId)
                ->setCreatedAt($complaintDate)
                ->setAppointmentRequired(true)
                ->setAppointmentContactInformation('Disponible entre 10h et 12h le lundi')
                ->setStatus($status)
                ->setOptinNotification($this->faker->boolean)
                ->setAlert($this->randomString([Complaint::ALERT_TSP, Complaint::ALERT_REGISTERED_VEHICLE, true === $victimOfViolence ? Complaint::ALERT_VIOLENCE : null, null]))
                ->setUnitAssigned($unit)
                ->setIdentity(
                    $this->newIdentity()
                )
                ->setConsentContactSMS(true)
                ->setConsentContactEmail(true)
                ->setConsentContactPortal(true)
                ->setFacts(
                    (new Facts())
                        ->setVictimOfViolence($victimOfViolence)
                        ->setVictimOfViolenceText(true === $victimOfViolence ? 'Frappé au visage' : null)
                        ->setNatures([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])
                        ->setDescription($this->faker->text())
                        ->setExactDateKnown($exactDateKnown)
                        ->setStartDate($factsStartDate)
                        ->setEndDate(true === $exactDateKnown ? null : $factsStartDate->add(new \DateInterval('P1D')))
                        ->setExactPlaceUnknown(false)
                        ->setPlace((string) $this->randomString(['MAISON INDIVIDUELLE', 'APPARTEMENT', 'VOIE PUBLIQUE', 'COMMERCE', 'METRO', 'ECOLE']))
                        ->setStartAddress($this->faker->streetAddress.', '.$factsAddressCity.', '.$factsAddressPostcode)
                        ->setEndAddress($this->randomString([
                            null,
                            $this->faker->streetAddress.', '.$factsAddressCity.', '.$factsAddressPostcode,
                        ]))
                        ->setStartAddressCity($factsAddressCity)
                        ->setStartAddressPostalCode((string) $factsAddressPostcode)
                        ->setStartAddressInseeCode($this->insees[$factsAddressPostcode])
                        ->setStartAddressDepartment($this->departments[substr((string) $factsAddressPostcode, 0, 2)])
                        ->setStartAddressDepartmentNumber((int) substr((string) $factsAddressPostcode, 0, 2))
                        ->setStartAddressCountry('France')
                        ->setExactHourKnown($exactHourKnown)
                        ->setStartHour($factsStartHour)
                        ->setEndHour(Facts::EXACT_HOUR_KNOWN_NO === $exactHourKnown ? $factsStartHour->add(new \DateInterval('PT1H')) : null)
                        ->setAlertNumber($this->faker->randomDigit())
                )
                ->addObject(
                    (new MultimediaObject())
                        ->setStatus(AbstractObject::STATUS_STOLEN)
                        ->setNature('TELEPHONE PORTABLE')
                        ->setBrand('Apple')
                        ->setModel($this->randomString(['Iphone', 'Iphone 13', 'Iphone 14']))
                        ->setOperator($this->randomString(['Orange', 'SFR', 'Bouygues', 'Free']))
                        ->setSerialNumber('1234567890')
                        ->setStillOnWhenMobileStolen(true)
                        ->setKeyboardLockedWhenMobileStolen(false)
                        ->setPinEnabledWhenMobileStolen(true)
                        ->setMobileInsured(false)
                        ->setPhoneNumber($this->randomString(['06 53 98 74 77', '07 00 98 55 89', '06 77 99 55 11']))
                        ->setAmount($this->faker->numberBetween(500, 2000))
                        ->setFiles(['blank.pdf', 'iphone.png'])
                )->addObject(
                    (new MultimediaObject())
                        ->setStatus(AbstractObject::STATUS_DEGRADED)
                        ->setNature('TELEPHONE PORTABLE')
                        ->setBrand('Samsung')
                        ->setDescription('Un Samsung tout neuf')
                        ->setModel($this->randomString(['S20', 'S21', 'S22', 'S23']))
                        ->setOperator($this->randomString(['Orange', 'SFR', 'Bouygues', 'Free']))
                        ->setSerialNumber('987654321')
                        ->setStillOnWhenMobileStolen(true)
                        ->setKeyboardLockedWhenMobileStolen(true)
                        ->setPinEnabledWhenMobileStolen(true)
                        ->setMobileInsured(false)
                        ->setPhoneNumber($this->randomString(['06 53 98 74 77', '07 00 98 55 89', '06 77 99 55 11']))
                        ->setAmount($this->faker->numberBetween(500, 2000))
                )->addObject(
                    (new MultimediaObject())
                        ->setStatus(AbstractObject::STATUS_STOLEN)
                        ->setNature('AUTRE NATURE MULTIMEDIA')
                        ->setBrand('Sony')
                        ->setModel('Playstation 4')
                        ->setSerialNumber('12345678')
                        ->setDescription('Ma console')
                )
                ->setAdditionalInformation(
                    (new AdditionalInformation())
                        ->setCctvPresent($cctvPresent)
                        ->setCctvAvailable(AdditionalInformation::CCTV_PRESENT_YES === $cctvPresent ? $this->faker->boolean : null)
                        ->setSuspectsKnown($suspectsKnown)
                        ->setSuspectsKnownText(true === $suspectsKnown ? $this->randomString([
                            '2 hommes : Jean Dupont et Thomas DURAND',
                            'Mon frère',
                            'Mon voisin du dessous',
                        ]) : null)
                        ->setWitnessesPresent($witnessesPresent)
                        ->addWitness(
                            (new Witness())
                                ->setDescription($this->randomString([
                                    'Aurore Moulin',
                                    'Nicolas Morin',
                                    'Jade Degois',
                                ]))
                                ->setPhone('06 12 34 45 57')
                                ->setEmail('jean@example.com')
                        )
                        ->setFsiVisit($fsiVisit)
                        ->setObservationMade(true === $fsiVisit ? $this->faker->boolean : null)
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est un commentaire.')
                        ->setAuthor($this->randomUser($this->users))
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est un autre commentaire.')
                        ->setAuthor($this->randomUser($this->users))
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est (encore) un autre commentaire.')
                        ->setAuthor($this->randomUser($this->users))
                )->addComment(
                    (new Comment())
                        ->setContent('Commentaire.')
                        ->setAuthor($this->randomUser($this->users))
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est un commentaire différent.')
                        ->setAuthor($this->randomUser($this->users))
                )
                ->setAssignedTo(Complaint::STATUS_ASSIGNED === $status ?
                    $manager->getRepository(User::class)->findOneBy(['number' => $agentNumber]) :
                    null
                );

            /* Person Legal Representative must be hidden for the experimentation */
            // if (Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE === $complaint->getIdentity()?->getDeclarantStatus()) {
            //     $complaint->setPersonLegalRepresented(
            //         $this->newIdentity(true)
            //     );
            // }

            if (Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $complaint->getIdentity()?->getDeclarantStatus()) {
                $complaint->setCorporationRepresented(
                    (new Corporation())
                        ->setSiretNumber((string) $this->randomString(['12345678911111', '98765432100000', '13243546800000']))
                        ->setCompanyName($this->faker->company)
                        ->setDeclarantPosition('PDG')
                        ->setContactEmail($this->faker->companyEmail)
                        ->setPhone($this->faker->phoneNumber)
                        ->setCountry('France')
                        ->setNationality('FRANCAISE')
                        ->setDepartment($this->departments[substr($this->identityAddressPostcode, 0, 2)])
                        ->setDepartmentNumber((int) substr($this->identityBirthPostcode, 0, 2))
                        ->setCity($this->identityAddressCity)
                        ->setPostCode($this->identityAddressPostcode)
                        ->setInseeCode($this->insees[$this->identityAddressPostcode])
                        ->setStreetNumber(1)
                        ->setStreetType('Rue')
                        ->setStreetName('Test')
                        ->setAddress($this->faker->address)
                );
            }

            if ($this->faker->boolean(30)) {
                $complaint->addObject(
                    (new AdministrativeDocument())
                        ->setStatus(AbstractObject::STATUS_STOLEN)
                        ->setIssuingCountry('France')
                        ->setType('Permis de conduire')
                        ->setDescription('Description Permis de conduire')
                        ->setNumber('1234')
                        ->setIssuedBy('Préfecture de Paris')
                        ->setIssuedOn(new \DateTimeImmutable('2010-01-01'))
                        ->setValidityEndDate(new \DateTimeImmutable('2030-01-01'))
                        ->setOwned(true)
                );
            }

            if ($this->faker->boolean(30)) {
                $complaint->addObject(
                    (new PaymentMethod())
                        ->setStatus(AbstractObject::STATUS_STOLEN)
                        ->setType('CARTE BANCAIRE')
                        ->setDescription((string) $this->randomString(['Visa principale', 'Mastercard']))
                        ->setBank($this->randomString(['Crédit Agricole', 'Caisse d\'épargne', 'LCL']))
                        ->setBankAccountNumber('987654321')
                        ->setCreditCardNumber('4624 7482 3324 9080')
                );
            }

            if ($this->faker->boolean(30)) {
                $complaint->addObject(
                    (new SimpleObject())
                        ->setStatus(AbstractObject::STATUS_DEGRADED)
                        ->setNature((string) $this->randomString(['Blouson', 'Guitare', 'Sac à dos']))
                        ->setDescription((string) $this->randomString(['De couleur Rouge', 'De couleur Noire', 'De couleur Bleue']))
                        ->setSerialNumber($this->randomString(['123456789', '987654321', '132435468']))
                        ->setQuantity(1)
                        ->setAmount($this->faker->numberBetween(100, 500))
                );
            }

            if (Complaint::ALERT_REGISTERED_VEHICLE === $complaint->getAlert()) {
                $complaint->addObject(
                    (new Vehicle())
                        ->setStatus(AbstractObject::STATUS_STOLEN)
                        ->setNature('VOITURE PARTICULIERE')
                        ->setBrand('Citroën')
                        ->setModel($this->randomString(['C3', 'C4', 'DS4', 'DS3']))
                        ->setRegistrationNumber($this->randomString(['AA-123-AA', 'BB-345-BB', 'CC-432-CC', 'DD-890-DD']))
                        ->setRegistrationCountry('France')
                        ->setInsuranceCompany($this->randomString(['AXA', 'Matmut', 'MAAF']))
                        ->setInsuranceNumber($this->randomString(['1458R147R', '8912T654T', '3278V265V']))
                        ->setAmount($this->faker->numberBetween(5000, 20000))
                );
            }

            $complaint->addObject(
                (new Vehicle())
                    ->setStatus(AbstractObject::STATUS_DEGRADED)
                    ->setNature('VOITURE PARTICULIERE')
                    ->setBrand('Peugeot')
                    ->setModel($this->randomString(['208', '308', '3008', '5008']))
                    ->setRegistrationNumber($this->randomString(['AA-123-AA', 'BB-345-BB', 'CC-432-CC', 'DD-890-DD']))
                    ->setRegistrationCountry('France')
                    ->setInsuranceCompany($this->randomString(['AXA', 'Matmut', 'MAAF']))
                    ->setInsuranceNumber($this->randomString(['1458R147R', '8912T654T', '3278V265V']))
                    ->setDegradationDescription($this->randomString(['Rétroviseur cassé', 'Pare-brise cassé', 'Portière rayée']))
                    ->setAmount($this->faker->numberBetween(5000, 20000))
            );

            if (true === $this->faker->boolean(30)) {
                $complaint->getFacts()?->setNatures([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION]);
            }

            $manager->persist($complaint);
        }

        $manager->flush();
        $manager->clear();

        /** @var ComplaintRepository $complaintRepository */
        $complaintRepository = $manager->getRepository(Complaint::class);
        $complaints = $complaintRepository->findAll();

        foreach ($complaints as $complaint) {
            /** @var User $agent */
            if ($agent = $complaint->getAssignedTo()) {
                $manager->persist(
                    $agent->addNotification($this->notificationFactory->createForComplaintAssigned($complaint))
                );
            }
        }

        $this->complaintNotification->setComplaintDeadlineExceededNotification($complaintRepository->getNotifiableComplaintsForProcessingDeadline());

        $manager->flush();
        $manager->clear();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function newIdentity(bool $victim = false): Identity
    {
        /** @var int $declarantStatus */
        $declarantStatus = $victim ? Identity::DECLARANT_STATUS_VICTIM : $this->faker->randomElement([
            Identity::DECLARANT_STATUS_VICTIM,
            /* Person Legal Representative must be hidden for the experimentation */
            // Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE,
            Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE]);

        return (new Identity())
            ->setFirstname($this->faker->firstName($this->identityGender))
            ->setLastname(mb_strtoupper($this->faker->lastName))
            ->setCivility('male' === $this->identityGender ? Identity::CIVILITY_MALE : Identity::CIVILITY_FEMALE)
            ->setFamilySituation($this->randomString([
                'Célibataire',
                'Concubinage',
                'Marié(e)',
                'Divorcé(e)',
                'Pacsé(e)',
                'Veuf(ve)',
            ]))
            ->setDeclarantStatus($declarantStatus)
            ->setBirthday(new \DateTimeImmutable((string) $this->randomString([
                    '1978-03-16',
                    '1997-12-07',
                    '2000-06-08',
                    '1967-10-09',
                ]
            )))
            ->setBirthCountry('France')
            ->setNationality('FRANCAISE')
            ->setBirthDepartment($this->departments[substr($this->identityBirthPostcode, 0, 2)])
            ->setBirthCity($this->places[$this->identityBirthPostcode])
            ->setBirthPostalCode($this->identityBirthPostcode)
            ->setBirthInseeCode($this->insees[$this->identityBirthPostcode])
            ->setBirthDepartmentNumber((int) substr($this->identityBirthPostcode, 0, 2))
            ->setAddress($this->identityAddressStreetAddress.', '.$this->identityAddressCity.', '.$this->identityAddressPostcode)
            ->setAddressStreetNumber('1')
            ->setAddressStreetType('Rue')
            ->setAddressStreetName('Test')
            ->setAddressCity($this->identityAddressCity)
            ->setAddressInseeCode($this->insees[$this->identityAddressPostcode])
            ->setAddressPostcode($this->identityAddressPostcode)
            ->setAddressDepartment($this->departments[substr($this->identityAddressPostcode, 0, 2)])
            ->setAddressDepartmentNumber((int) substr($this->identityBirthPostcode, 0, 2))
            ->setAddressCountry('France')
            ->setMobilePhone((string) $this->randomString(['06 53 98 74 77', '07 00 98 55 89', '06 77 99 55 11']))
            ->setEmail($this->faker->email)
            ->setJob($this->faker->jobTitle)
            ->setJobThesaurus('FONCTIONNAIRE')
            ->setAlertNumber($this->faker->randomDigit());
    }

    /**
     * @param array<int, string|null> $values
     */
    private function randomString(array $values): ?string
    {
        /** @var string|null $randomString */
        $randomString = $this->faker->randomElement($values);

        return $randomString;
    }

    /**
     * @param array<User> $values
     */
    private function randomUser(array $values): User
    {
        /** @var User $randomUser */
        $randomUser = $this->faker->randomElement($values);

        return $randomUser;
    }
}
