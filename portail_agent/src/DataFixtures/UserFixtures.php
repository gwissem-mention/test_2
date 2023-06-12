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
            '68809',
            '85193',
            '85194',
            '68830',
            '68849',
            '72903',
            '102601',
            '110279',
            '110280',
            '110281',
            '110282',
            '102645',
            '102647',
            '72727',
            '68905',
            '74097',
            '102765',
            '102812',
            '102821',
            '102840',
            '74181',
            '65575',
            '105296',
            '105297',
            '66043',
            '66063',
            '66082',
            '105405',
            '63139',
            '63169',
            '63179',
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
                'appellation' => 'Jean DUPONT',
                'number' => 'H3U3XCGD',
                'institution' => Institution::PN,
                'serviceCode' => '103131', // CSP VOIRON/SVP/UPS/BRIGADE DE JOUR
                'supervisor' => false,
            ],
            [
                'appellation' => 'Thomas DURAND',
                'number' => 'PR5KTZ9R',
                'institution' => Institution::GN,
                'serviceCode' => '3002739', // Brigade de proximité de Voiron
                'supervisor' => true,
            ],
            [
                'appellation' => 'Louise THOMAS',
                'number' => 'H3U3XCGF',
                'institution' => Institution::PN,
                'serviceCode' => '103131', // CSP VOIRON/SVP/UPS/BRIGADE DE JOUR
                'supervisor' => true,
            ],
            [
                'appellation' => 'Julie RICHARD',
                'number' => 'PR5KTZ9C',
                'institution' => Institution::GN,
                'serviceCode' => '3002739', // Brigade de proximité de Voiron
                'supervisor' => false,
            ],
            [
                'appellation' => 'Philippe RIVIERE',
                'number' => 'PR5KTQSD',
                'institution' => Institution::GN,
                'serviceCode' => '3002739', // Brigade de proximité de Voiron
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
