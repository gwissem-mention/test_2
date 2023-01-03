<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\City;

use App\Referential\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CityMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $cities = [
            (new City())
                ->setLabel('Paris')
                ->setDepartment('75')
                ->setPostCode('75000')
                ->setInseeCode('75056'),
            (new City())
                ->setLabel('Paris 7e arrondissement')
                ->setDepartment('75')
                ->setPostCode('75007')
                ->setInseeCode('75107'),
            (new City())
                ->setLabel('Lyon')
                ->setDepartment('69')
                ->setPostCode('69000')
                ->setInseeCode('69123'),
        ];

        foreach ($cities as $city) {
            $manager->persist($city);
        }

        $manager->flush();
    }
}
