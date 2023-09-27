<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\AppEnum\Institution;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private const NB_USERS_BY_UNIT = 2;
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
        return ['default', 'ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(1);

        $users = [
            [
                'appellation' => 'Frédérique BONNIN',
                'number' => 'WTDAXALL',
                'institution' => Institution::PN,
                'serviceCode' => '74181', // COMMISSARIAT DE POLICE D'ARCACHON
                'supervisor' => false,
            ],
            ['appellation' => 'Thomas DURAND',
                'number' => 'PR5KTZ9R',
                'institution' => Institution::GN,
                'serviceCode' => '3009446', // BRIGADE TERRITORIALE AUTONOME DE CESTAS
                'supervisor' => true,
            ],
            [
                'appellation' => 'Julie RICHARD',
                'number' => 'PR5KTZ9C',
                'institution' => Institution::GN,
                'serviceCode' => '3009446', // BRIGADE TERRITORIALE AUTONOME DE CESTAS
                'supervisor' => false,
            ],
            [
                'appellation' => 'Philippe RIVIERE',
                'number' => 'PR5KTQSD',
                'institution' => Institution::GN,
                'serviceCode' => '3009446', // BRIGADE TERRITORIALE AUTONOME DE CESTAS
                'supervisor' => false,
            ],
        ];

        foreach (self::SERVICES as $service) {
            for ($i = 0; $i < self::NB_USERS_BY_UNIT; ++$i) {
                $users[] = [
                    'appellation' => $faker->firstName().' '.strtoupper($faker->lastName()),
                    'number' => strtoupper($faker->unique()->bothify('????????')),
                    'institution' => Institution::PN,
                    'serviceCode' => $service,
                    'supervisor' => 1 === $i,
                ];
            }
        }

        foreach ($users as $user) {
            $manager->persist((new User($user['number'], $user['institution'], $user['supervisor']))
                ->setAppellation($user['appellation'])
                ->setServiceCode($user['serviceCode']));
        }

        $manager->flush();
    }
}
