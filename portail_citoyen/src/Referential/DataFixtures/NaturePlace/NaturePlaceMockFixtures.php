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
            new NaturePlace('Lieu de culte ou de recueillement'),
            new NaturePlace('Lieu de loisirs'),
            new NaturePlace('Établissement scolaire'),
            new NaturePlace('Transports'),
        ];

        foreach ($naturesPlacesLv1 as $naturesPlace) {
            $manager->persist($naturesPlace);
        }

        $manager->flush();

        $home = $manager->getRepository(NaturePlace::class)->findOneBy(['label' => 'Domicile, logement et dépendances']);
        $worship = $manager->getRepository(NaturePlace::class)->findOneBy(['label' => 'Lieu de culte ou de recueillement']);
        $leisure = $manager->getRepository(NaturePlace::class)->findOneBy(['label' => 'Lieu de loisirs']);
        $school = $manager->getRepository(NaturePlace::class)->findOneBy(['label' => 'Établissement scolaire']);

        $naturesPlacesLv2 = [
            new NaturePlace('Maison', 'MAISON INDIVIDUELLE', $home),
            new NaturePlace('Appartement', 'APPARTEMENT', $home),
            new NaturePlace('Cimetière', 'CIMETIERE', $worship),
            new NaturePlace('Monument aux morts', 'MONUMENT AUX MORTS', $worship),
            new NaturePlace('Camping', 'CAMPING', $leisure),
            new NaturePlace('Centre sportif', 'CENTRE SPORTIF', $leisure),
            new NaturePlace('Établissement enseignement supérieur', 'ETABLISSEMENT ENSEIGNEMENT SUPERIEUR', $school),
        ];

        foreach ($naturesPlacesLv2 as $naturesPlace) {
            $manager->persist($naturesPlace);
        }

        $manager->flush();
    }
}
