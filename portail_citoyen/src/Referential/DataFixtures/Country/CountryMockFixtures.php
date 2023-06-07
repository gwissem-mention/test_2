<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Country;

use App\Referential\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CountryMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $countries = [
            new Country('BG', 'Bulgarie', 99111),
            new Country('HR', 'Croatie', 99119),
            new Country('FR', 'France', 99160),
            new Country('ES', 'Espagne', 99134),
        ];

        foreach ($countries as $country) {
            $manager->persist($country);
        }

        $manager->flush();
    }
}
