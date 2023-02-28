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
        $unit = (new Unit(
            null,
            null,
            '3002739',
            'Brigade de proximité de Voiron',
            'Brigade de proximité de Voiron',
            'Voiron',
            '75',
            '630',
            Institution::GN
        ));

        $manager->persist($unit);

        $unit = (new Unit(
            null,
            null,
            '103131',
            'CSP VOIRON/SVP/UPS/BRIGADE DE JOUR',
            'CSP VOIRON/SVP/UPS/BRIGADE DE JOUR',
            'Voiron',
            '75',
            '630',
            Institution::PN
        ));

        $manager->persist($unit);

        $manager->flush();
    }
}
