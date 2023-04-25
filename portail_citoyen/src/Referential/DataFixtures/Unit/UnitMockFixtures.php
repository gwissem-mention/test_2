<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Unit;

use App\Enum\Institution;
use App\Referential\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UnitMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $units = [
            new Unit(
                '3002739',
                '2739',
                'Brigade de proximité de Voiron',
                '45.36849',
                '5.58565',
                '2 Rue Danton 38500 VOIRON',
                '04 76 05 01 83',
                '$Vendredi : 14h00-18h00',
                '1008952',
                Institution::GN
            ),
            new Unit(
                '3026751',
                '26751',
                'Brigade de proximité de Renage',
                '45.33741',
                '5.48426',
                '339 Rue de la République 38140 RENAGE',
                '04 76 65 30 17',
                '$Lundi : 8h00-12h00 14h00-18h00$Mardi : 8h00-12h00 14h00-18h00$Mercredi : 8h00-12h00 14h00-18h00$Jeudi : 8h00-12h00 14h00-18h00$Vendredi : 8h00-12h00 14h00-18h00$Samedi : 8h00-12h00 14h00-18h00$Dimanche : 9h00-12h00 15h00-18h00$Jours Fériés : 9h00-12h00 15h00-18h00',
                '1008950',
                Institution::GN
            ),
        ];

        foreach ($units as $unit) {
            $manager->persist($unit);
        }

        $manager->flush();
    }
}
