<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\RegisteredVehicleNature;

use App\Referential\Entity\RegisteredVehicleNature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RegisteredVehicleNatureMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $natures = [
            '2' => 'moto',
            '7' => 'camion',
        ];

        foreach ($natures as $natureCode => $natureLabel) {
            $manager->persist(new RegisteredVehicleNature((string) $natureLabel, (string) $natureCode));
        }

        $manager->flush();
    }
}
