<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Personnel;

use App\Referential\Entity\Personnel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonnelMockFixtures extends Fixture implements FixtureGroupInterface
{
    private const NB_PERSONNELS_BY_UNIT = 2;
    private const SERVICES = [
            '105893',
            '65855',
            '65857',
            '65861',
            '65863',
            '65971',
            '65973',
            '65977',
            '65550',
            '74181',
            '102765',
            '74097',
            '5905',
            '7035',
            '6624',
            '5905',
        ];

    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(1);

        $personnels = [
            [
                'personnelId' => '1',
                'appellation' => 'Frédérique BONNIN',
                'matricule' => 'WTDAXALL',
                'serviceCode' => '74181', // COMMISSARIAT DE POLICE D'ARCACHON
            ],
            [
                'personnelId' => '2',
                'appellation' => 'Thomas DURAND',
                'matricule' => 'PR5KTZ9R',
                'serviceCode' => '3009446', // BRIGADE TERRITORIALE AUTONOME DE CESTAS
            ],
            [
                'personnelId' => '3',
                'appellation' => 'Julie RICHARD',
                'matricule' => 'PR5KTZ9C',
                'serviceCode' => '3009446', // BRIGADE TERRITORIALE AUTONOME DE CESTAS
            ],
            [
                'personnelId' => '4',
                'appellation' => 'Philippe RIVIERE',
                'matricule' => 'PR5KTQSD',
                'serviceCode' => '3009446', // BRIGADE TERRITORIALE AUTONOME DE CESTAS
            ],
        ];

        foreach (self::SERVICES as $service) {
            for ($i = 0; $i < self::NB_PERSONNELS_BY_UNIT; ++$i) {
                $personnels[] = [
                    'personnelId' => (string) $faker->numberBetween(5, 1000),
                    'appellation' => $faker->firstName().' '.strtoupper($faker->lastName()),
                    'matricule' => strtoupper($faker->unique()->bothify('????????')),
                    'serviceCode' => $service,
                ];
            }
        }

        foreach ($personnels as $user) {
            $manager->persist(new Personnel(
                $user['personnelId'],
                $user['appellation'],
                $user['serviceCode'],
                $user['matricule']
            ));
        }

        $manager->flush();
    }
}
