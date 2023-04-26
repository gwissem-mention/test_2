<?php

declare(strict_types=1);

namespace App\DataFixtures\Complaint;

use App\DataFixtures\UserFixtures;
use App\Entity\AdditionalInformation;
use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\Corporation;
use App\Entity\Facts;
use App\Entity\FactsObjects\AdministrativeDocument;
use App\Entity\FactsObjects\MultimediaObject;
use App\Entity\FactsObjects\PaymentMethod;
use App\Entity\FactsObjects\SimpleObject;
use App\Entity\FactsObjects\Vehicle;
use App\Entity\Identity;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Notification\ComplaintNotification;
use App\Repository\ComplaintRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

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
        private readonly ComplaintNotification $complaintNotification
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
                Complaint::STATUS_REASSIGNMENT_PENDING,
                Complaint::STATUS_REJECTED,
                Complaint::STATUS_ONGOING_LRP,
                Complaint::STATUS_MP_DECLARANT,
                Complaint::STATUS_UNIT_REASSIGNMENT_PENDING,
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
            $exactHourKnown = $this->faker->randomElement([Facts::EXACT_HOUR_KNOWN_NO, Facts::EXACT_HOUR_KNOWN_YES]);

            $cctvPresent = $this->faker->randomElement([AdditionalInformation::CCTV_PRESENT_YES, AdditionalInformation::CCTV_PRESENT_NO, AdditionalInformation::CCTV_PRESENT_DONT_KNOW]);
            $suspectsKnown = $this->faker->boolean;
            $witnessesPresent = $this->faker->boolean;
            $fsiVisit = $this->faker->boolean;
            $victimOfViolence = $this->faker->boolean;
            $stolenVehicle = $this->faker->boolean(30);

            if ('103131' === $unit) {
                $agentNumber = $this->faker->randomElement(['H3U3XCGD', 'H3U3XCGF']);
            } else {
                $agentNumber = $this->faker->randomElement(['PR5KTZ9R', 'PR5KTQSD']);
            }

            $complaint = (new Complaint())
                ->setTest(true)
                ->setCreatedAt($complaintDate)
                ->setAppointmentDate($complaintDate->add(new \DateInterval('P1D')))
                ->setStatus($status)
                ->setOptinNotification($this->faker->boolean)
                ->setAlert($this->faker->randomElement([Complaint::ALERT_TSP, Complaint::ALERT_REGISTERED_VEHICLE, true === $victimOfViolence ? Complaint::ALERT_VIOLENCE : null, null]))
                ->setUnitAssigned($unit)
                ->setIdentity(
                    $this->newIdentity()
                )
                ->setFacts(
                    (new Facts())
                        ->setNatures([$this->faker->randomElement([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])])
                        ->setDescription($this->faker->text())
                        ->setExactDateKnown($exactDateKnown)
                        ->setStartDate($factsStartDate)
                        ->setEndDate(true === $exactDateKnown ? null : $factsStartDate->add(new \DateInterval('P1D')))
                        ->setExactPlaceUnknown(false)
                        ->setPlace($this->faker->randomElement(['Domicile', 'Parking', 'Voie publique', 'Commerce', 'Transports en commun', 'Autre nature de lieu', 'Lieu indéterminé']))
                        ->setStartAddress($this->faker->streetAddress.', '.$factsAddressCity.', '.$factsAddressPostcode)
                        ->setEndAddress($this->faker->randomElement([
                            null,
                            $this->faker->streetAddress.', '.$factsAddressCity.', '.$factsAddressPostcode,
                        ]))
                        ->setCity($factsAddressCity)
                        ->setPostalCode((string) $factsAddressPostcode)
                        ->setInseeCode($this->insees[$factsAddressPostcode])
                        ->setDepartment($this->departments[substr((string) $factsAddressPostcode, 0, 2)])
                        ->setDepartmentNumber((int) substr((string) $factsAddressPostcode, 0, 2))
                        ->setCountry('France')
                        ->setExactHourKnown($exactHourKnown)
                        ->setStartHour($factsStartHour)
                        ->setEndHour(Facts::EXACT_HOUR_KNOWN_NO === $exactHourKnown ? $factsStartHour->add(new \DateInterval('PT1H')) : null)
                        ->setAlertNumber($this->faker->randomDigit())
                )
                ->addObject(
                    (new MultimediaObject())
                        ->setLabel('Téléphone mobile')
                        ->setBrand('Apple')
                        ->setModel($this->faker->randomElement(['Iphone', 'Iphone 13', 'Iphone 14']))
                        ->setOperator($this->faker->randomElement(['Orange', 'SFR', 'Bouygues', 'Free']))
                        ->setSerialNumber(1234567890)
                        ->setPhoneNumber($this->faker->mobileNumber)
                        ->setAmount($this->faker->numberBetween(500, 2000))
                )
                ->addObject(
                    (new MultimediaObject())
                        ->setLabel('Téléphone mobile')
                        ->setBrand('Samsung')
                        ->setModel($this->faker->randomElement(['S20', 'S21', 'S22', 'S23']))
                        ->setOperator($this->faker->randomElement(['Orange', 'SFR', 'Bouygues', 'Free']))
                        ->setSerialNumber(987654321)
                        ->setPhoneNumber($this->faker->mobileNumber)
                        ->setAmount($this->faker->numberBetween(500, 2000))
                )
                ->setAdditionalInformation(
                    (new AdditionalInformation())
                        ->setCctvPresent($cctvPresent)
                        ->setCctvAvailable(AdditionalInformation::CCTV_PRESENT_YES === $cctvPresent ? $this->faker->boolean : null)
                        ->setSuspectsKnown($suspectsKnown)
                        ->setSuspectsKnownText(true === $suspectsKnown ? $this->faker->randomElement([
                            '2 hommes : Jean Dupont et Thomas DURAND',
                            'Mon frère',
                            'Mon voisin du dessous',
                        ]) : null)
                        ->setWitnessesPresent($witnessesPresent)
                        ->setWitnessesPresentText(true === $witnessesPresent ? $this->faker->randomElement([
                            'Aurore Moulin',
                            'Nicolas Morin',
                            'Jade Degois',
                        ]) : null)
                        ->setFsiVisit($fsiVisit)
                        ->setObservationMade(true === $fsiVisit ? $this->faker->boolean : null)
                        ->setVictimOfViolence($victimOfViolence)
                        ->setVictimOfViolenceText(true === $victimOfViolence ? 'Frappé au visage' : null)
                        ->setDescription("Vol d'un Iphone 13")
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est un commentaire.')
                        ->setAuthor($this->faker->randomElement($this->users))
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est un autre commentaire.')
                        ->setAuthor($this->faker->randomElement($this->users))
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est (encore) un autre commentaire.')
                        ->setAuthor($this->faker->randomElement($this->users))
                )->addComment(
                    (new Comment())
                        ->setContent('Commentaire.')
                        ->setAuthor($this->faker->randomElement($this->users))
                )
                ->addComment(
                    (new Comment())
                        ->setContent('Ceci est un commentaire différent.')
                        ->setAuthor($this->faker->randomElement($this->users))
                )
                ->setAssignedTo(Complaint::STATUS_ASSIGNED === $status ?
                    $manager->getRepository(User::class)->findOneBy(['number' => $agentNumber]) :
                    null
                );

            if (Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE === $complaint->getIdentity()?->getDeclarantStatus()) {
                $complaint->setPersonLegalRepresented(
                    $this->newIdentity(true)
                );
            }

            if (Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE === $complaint->getIdentity()?->getDeclarantStatus()) {
                $complaint->setCorporationRepresented(
                    (new Corporation())
                        ->setSirenNumber($this->faker->siren)
                        ->setCompanyName($this->faker->company)
                        ->setDeclarantPosition('PDG')
                        ->setContactEmail($this->faker->companyEmail)
                        ->setPhone($this->faker->phoneNumber)
                        ->setCountry('France')
                        ->setNationality('Française')
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
                        ->setType('Permis de conduire')
                );
            }

            if ($this->faker->boolean(30)) {
                $complaint->addObject(
                    (new PaymentMethod())
                        ->setType('Carte Bancaire')
                        ->setDescription($this->faker->randomElement(['Visa principale', 'Mastercard']))
                        ->setBank($this->faker->randomElement(['Crédit Agricole', 'Caisse d\'épargne', 'LCL']))
                );
            }

            if ($this->faker->boolean(30)) {
                $complaint->addObject(
                    (new SimpleObject())
                        ->setNature($this->faker->randomElement(['Blouson', 'Guitare', 'Sac à dos']))
                        ->setDescription($this->faker->randomElement(['De couleur Rouge', 'De couleur Noire', 'De couleur Bleue']))
                        ->setAmount($this->faker->numberBetween(100, 500))
                );
            }

            if (Complaint::ALERT_REGISTERED_VEHICLE === $complaint->getAlert()) {
                $complaint->addObject(
                    (new Vehicle())
                        ->setLabel('Voiture')
                        ->setBrand('Citroën')
                        ->setModel($this->faker->randomElement(['C3', 'C4', 'DS4', 'DS3']))
                        ->setRegistrationNumber($this->faker->randomElement(['AA-123-AA', 'BB-345-BB', 'CC-432-CC', 'DD-890-DD']))
                        ->setRegistrationCountry('France')
                        ->setInsuranceCompany($this->faker->randomElement(['AXA', 'Matmut', 'MAAF']))
                        ->setInsuranceNumber($this->faker->randomElement(['1458R147R', '8912T654T', '3278V265V']))
                        ->setAmount($this->faker->numberBetween(5000, 20000))
                );
            }

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
        return (new Identity())
            ->setFirstname($this->faker->firstName($this->identityGender))
            ->setLastname(mb_strtoupper($this->faker->lastName))
            ->setCivility('male' === $this->identityGender ? Identity::CIVILITY_MALE : Identity::CIVILITY_FEMALE)
            ->setDeclarantStatus($victim ? Identity::DECLARANT_STATUS_VICTIM : $this->faker->randomElement([Identity::DECLARANT_STATUS_VICTIM, Identity::DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE, Identity::DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE]))
            ->setBirthday(new \DateTimeImmutable($this->faker->randomElement([
                    '1978-03-16',
                    '1997-12-07',
                    '2000-06-08',
                    '1967-10-09',
                ]
            )))
            ->setBirthCountry('France')
            ->setNationality('Française')
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
            ->setMobilePhone($this->faker->mobileNumber)
            ->setEmail($this->faker->email)
            ->setJob($this->faker->jobTitle)
            ->setAlertNumber($this->faker->randomDigit());
    }
}
