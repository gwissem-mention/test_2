<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\DocumentType;

use App\Referential\Entity\DocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class DocumentTypeMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $documentTypes = [
            "CARTE NATIONALE D'IDENTITE",
            'PERMIS DE CONDUIRE',
            'CARTE GRISE',
            'PASSEPORT',
            'CARTE DE SEJOUR',
            'CARTE DE REFUGIE',
            'CARTE PROFESSIONNELLE',
            'LIVRET DE FAMILLE',
            'TITRE DE TRANSPORT',
            "FICHE D'ETAT CIVIL",
            'CARTE VITALE',
            'CARTE MUTUELLE',
            'ORDONNANCIER',
            'BADGE',
            'AUTRE NATURE DOCUMENT',
        ];

        foreach ($documentTypes as $documentType) {
            $manager->persist(new DocumentType($documentType));
        }

        $manager->flush();
    }
}
