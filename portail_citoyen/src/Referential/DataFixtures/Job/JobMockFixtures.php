<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Job;

use App\Referential\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class JobMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $jobs = [
            new Job('1', 'Abat-jouriste', 'Abat-jouriste'),
            new Job('2', 'Abatteur de bestiaux', 'Abatteuse de bestiaux'),
            new Job('3', 'Accompagnateur musical', 'Accompagnatrice musicale'),
        ];

        foreach ($jobs as $job) {
            $manager->persist($job);
        }

        $manager->flush();
    }
}
