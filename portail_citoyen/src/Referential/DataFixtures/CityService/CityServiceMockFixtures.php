<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\CityService;

use App\Referential\Entity\CityService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CityServiceMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $cityServices = [
            new CityService('33063', '74097', '74097', '1'),
            new CityService('75111', '17501026', '17501026', '1'),
        ];

        foreach ($cityServices as $cityService) {
            $manager->persist($cityService);
        }

        $manager->flush();
    }
}
