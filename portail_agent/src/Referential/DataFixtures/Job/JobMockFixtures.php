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
            new Job('abat-jouriste', 'Abat-jouriste', 'ARTISAN'),
            new Job('abatteur_de_bestiaux', 'Abatteur de bestiaux', 'TUEUR AUX ABATTOIRS'),
            new Job('accompagnateur_musical', 'Accompagnateur musical', 'MUSICIEN'),
            new Job('professeur_vacataire_en_lycee', 'Professeur vacataire en lycée', 'ENSEIGNANT'),
        ];

        foreach ($jobs as $job) {
            $manager->persist($job);
        }

        $manager->flush();
    }
}
