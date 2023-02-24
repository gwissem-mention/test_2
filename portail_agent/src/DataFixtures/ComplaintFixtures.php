<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\AdditionalInformation;
use App\Entity\Comment;
use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObject;
use App\Entity\Identity;
use App\Entity\User;
use App\Factory\NotificationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class ComplaintFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const COMPLAINTS_NB = 50;

    public function __construct(private readonly Generator $faker, private readonly NotificationFactory $notificationFactory)
    {
        $faker->seed(1);
    }

    public static function getGroups(): array
    {
        return ['default'];
    }

    public function load(ObjectManager $manager): void
    {
        $places = [
            '06000' => 'Nice',
            '13000' => 'Marseille',
            '75000' => 'Paris',
            '69000' => 'Lyon',
            '33000' => 'Bordeaux',
            '38000' => 'Grenoble',
        ];

        for ($i = 1; $i <= self::COMPLAINTS_NB; ++$i) {
            $factsStartDate = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('2023-01-01', '2023-01-31'));
            $complaintDate = \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('2023-02-01', '2023-02-15'));
            $factsStartHour = \DateTimeImmutable::createFromMutable($this->faker->dateTime('2023-01-31 10:00:00'));

            $identityGender = $this->faker->randomElement(['male', 'female']);

            /** @var string $status */
            $status = $this->faker->randomElement([
                Complaint::STATUS_ASSIGNMENT_PENDING,
                Complaint::STATUS_ASSIGNED,
                Complaint::STATUS_REJECTED,
                Complaint::STATUS_ONGOING_LRP,
                Complaint::STATUS_MP_DECLARANT,
            ]);
            $identityBirthPostcode = $this->faker->randomKey($places);
            $identityAddressPostcode = $this->faker->randomKey($places);
            $identityAddressCity = $places[$identityAddressPostcode];
            $factsAddressPostcode = $this->faker->randomKey($places);
            $factsAddressCity = $places[$factsAddressPostcode];

            /** @var string $unit */
            $unit = $this->faker->randomElement(['103131', '3002739']);
            $exactDateKnown = $this->faker->boolean;
            $exactHourKnown = $this->faker->randomElement([Facts::EXACT_HOUR_KNOWN_NO, Facts::EXACT_HOUR_KNOWN_YES]);

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
                ->setCreatedAt($complaintDate)
                ->setAppointmentDate($complaintDate->add(new \DateInterval('P1D')))
                ->setStatus($status)
                ->setOptinNotification($this->faker->boolean)
                ->setAlert($this->faker->randomElement(['Alerte PTS', true === $victimOfViolence ? 'Violence' : null, null]))
                ->setUnitAssigned($unit)
                ->setIdentity(
                    (new Identity())
                        ->setFirstname($this->faker->firstName($identityGender))
                        ->setLastname(mb_strtoupper($this->faker->lastName))
                        ->setCivility('male' === $identityGender ? Identity::CIVILITY_MALE : Identity::CIVILITY_FEMALE)
                        ->setDeclarantStatus(Identity::DECLARANT_STATUS_VICTIM)
                        ->setBirthday(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-60 years', '-20 years')))
                        ->setBirthCountry('France')
                        ->setNationality('Française')
                        ->setBirthDepartment(substr((string) $identityBirthPostcode, 0, 2))
                        ->setBirthCity($places[$identityBirthPostcode])
                        ->setAddress($this->faker->streetAddress.', '.$identityAddressCity.', '.$identityAddressPostcode)
                        ->setAddressCity((string) $identityAddressCity)
                        ->setAddressPostcode((string) $identityAddressPostcode)
                        ->setPhone($this->faker->mobileNumber)
                        ->setEmail($this->faker->email)
                        ->setJob($this->faker->jobTitle)
                        ->setAlertNumber($this->faker->randomDigit())
                )
                ->setFacts(
                    (new Facts())
                        ->setNatures([$this->faker->randomElement([Facts::NATURE_ROBBERY, Facts::NATURE_DEGRADATION])])
                        ->setExactDateKnown($exactDateKnown)
                        ->setStartDate($factsStartDate)
                        ->setEndDate(true === $exactDateKnown ? null : $factsStartDate->add(new \DateInterval('P1D')))
                        ->setPlace($this->faker->randomElement(['Domicile', 'Parking', 'Voie publique', 'Commerce', 'Transports en commun', 'Autre nature de lieu', 'Lieu indéterminé']))
                        ->setStartAddress($this->faker->streetAddress.', '.$factsAddressCity.', '.$factsAddressPostcode)
                        ->setEndAddress($this->faker->randomElement([
                            null,
                            $this->faker->streetAddress.', '.$factsAddressCity.', '.$factsAddressPostcode,
                        ]))
                        ->setExactHourKnown($exactHourKnown)
                        ->setStartHour($factsStartHour)
                        ->setEndHour(Facts::EXACT_HOUR_KNOWN_NO === $exactHourKnown ? $factsStartHour->add(new \DateInterval('PT1H')) : null)
                        ->setAlertNumber($this->faker->randomDigit())
                )
                ->addObject(
                    (new FactsObject())
                        ->setLabel('Téléphone mobile')
                        ->setBrand('Apple')
                        ->setModel($this->faker->randomElement(['Iphone', 'Iphone 13', 'Iphone 14']))
                        ->setOperator($this->faker->randomElement(['Orange', 'SFR', 'Bouygues', 'Free']))
                        ->setImei(1234567890)
                        ->setPhoneNumber($this->faker->mobileNumber)
                        ->setAmount($this->faker->numberBetween(500, 2000))
                )
                ->addObject(
                    (new FactsObject())
                        ->setLabel('Téléphone mobile')
                        ->setBrand('Samsung')
                        ->setModel($this->faker->randomElement(['S20', 'S21', 'S22', 'S23']))
                        ->setOperator($this->faker->randomElement(['Orange', 'SFR', 'Bouygues', 'Free']))
                        ->setImei(987654321)
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
                )
                ->setAssignedTo(Complaint::STATUS_ASSIGNED === $status ?
                    $manager->getRepository(User::class)->findOneBy(['number' => $agentNumber]) :
                    null
                );

            $manager->persist($complaint);
        }

        $manager->flush();

        /** @var User $user */
        $user = $manager->getRepository(User::class)->findOneBy([]);

        /** @var array<Complaint> $complaints */
        $complaints = $manager->getRepository(Complaint::class)->findBy([], [], 2);

        foreach ($complaints as $complaint) {
            $manager->persist(
                $user->addNotification($this->notificationFactory->createForComplaintAssigned($complaint))
            );
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
