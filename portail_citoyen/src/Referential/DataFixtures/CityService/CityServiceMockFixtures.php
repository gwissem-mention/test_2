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
            new CityService('38105', '3002739'),
            new CityService('38133', '3002739'),
            new CityService('38362', '3002739'),
            new CityService('38383', '3002739'),
            new CityService('38432', '3002739'),
            new CityService('38563', '66459'),
            new CityService('75111', '17501026'),
        ];

        foreach ($cityServices as $cityService) {
            $manager->persist($cityService);
        }

        $manager->flush();
    }
}
