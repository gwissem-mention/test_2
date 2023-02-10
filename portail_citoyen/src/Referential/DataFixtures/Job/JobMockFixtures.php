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
            new Job('46B3', 'Acheteurs (non cadres) et responsables des achats'),
            new Job('37D2', 'Acheteurs du commerce'),
            new Job('38F1', 'Acheteurs et cadres des achats du BTP et de l’industrie'),
            new Job('22D3', 'Agents commerciaux immobiliers'),
            new Job('52A1', "Agents d'accueil et de guichet des administrations publiques"),
            new Job('48A1', 'Agents de maîtrise de l’agriculture, des travaux forestiers et de la pêche'),
        ];

        foreach ($jobs as $job) {
            $manager->persist($job);
        }

        $manager->flush();
    }
}
