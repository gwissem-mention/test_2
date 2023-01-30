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
            new Job('31B1', 'Avocats'),
            new Job('43A2', 'Sages-femmes'),
            new Job('43D6', 'Éducateurs de jeunes enfants'),
        ];

        foreach ($jobs as $job) {
            $manager->persist($job);
        }

        $manager->flush();
    }
}
