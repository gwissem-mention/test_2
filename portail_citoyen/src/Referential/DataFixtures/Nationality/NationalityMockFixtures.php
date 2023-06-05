<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Nationality;

use App\Referential\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class NationalityMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $nationalities = [
            (new Nationality())
                ->setLabel('FRANCAISE')
                ->setCode('23'),
             (new Nationality())
                ->setLabel('ESPAGNOLE')
                ->setCode('24'),
             (new Nationality())
                ->setLabel('ALLEMANDE')
                ->setCode('17'),
        ];

        foreach ($nationalities as $nationality) {
            $manager->persist($nationality);
        }

        $manager->flush();
    }
}
