<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\CityUnit;

use App\Referential\Entity\CityUnit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CityUnitMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $cityUnits = [
            new CityUnit('38332', '26751'),
        ];

        foreach ($cityUnits as $cityUnit) {
            $manager->persist($cityUnit);
        }

        $manager->flush();
    }
}
