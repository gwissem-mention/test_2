<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\NaturePlace;

use App\Referential\Entity\NaturePlace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class NaturePlaceMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $naturesPlacesLv1 = [
            new NaturePlace('Domicile, logement et dépendances'),
            new NaturePlace('Voie publique', 'VOIE PUBLIQUE'),
            new NaturePlace('Téléphone', 'RESEAU TELEPHONIQUE'),
            new NaturePlace('Parking', 'PARKING'),
            new NaturePlace('Internet', 'INTERNET'),
        ];

        foreach ($naturesPlacesLv1 as $naturesPlace) {
            $manager->persist($naturesPlace);
        }

        $manager->flush();

        $home = $manager->getRepository(NaturePlace::class)->findOneBy(['label' => 'Domicile, logement et dépendances']);

        $naturesPlacesLv2 = [
            new NaturePlace('Maison', 'MAISON INDIVIDUELLE', $home),
            new NaturePlace('Appartement', 'APPARTEMENT', $home),
        ];

        foreach ($naturesPlacesLv2 as $naturesPlace) {
            $manager->persist($naturesPlace);
        }

        $manager->flush();
    }
}
