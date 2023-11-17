<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Unit;

use App\AppEnum\Institution;
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
        $units = [
            new Unit(
                '1476',
                '5905',
                'Brigade territoriale autonome de Villegouge',
                '44.96757',
                '-0.30876',
                '5 Route des Acacias 33141 VILLEGOUGE',
                '05 57 84 86 50',
                '$Lundi : 14h00-17h00$Mardi : 14h00-17h00$Mercredi : 14h00-17h00$Jeudi : 14h00-17h00$Vendredi : 14h00-17h00$Samedi : 14h00-17h00',
                '1004099',
                Institution::GN
            ),
            new Unit(
                '1401',
                '7035',
                'Brigade territoriale autonome de Biganos',
                '44.63676',
                '-0.97088',
                '69 Avenue de la Côte-d\'Argent FACTURE 33380 BIGANOS',
                '05 57 17 06 80',
                '$Lundi : 8h00-12h00$Mardi : 8h00-12h00$Mercredi : 8h00-12h00$Jeudi : 8h00-12h00$Vendredi : 8h00-12h00$Samedi : 8h00-12h00$Dimanche : 9h00-12h00$Jours Fériés : 9h00-12h00',
                '1004031',
                Institution::GN
            ),
            new Unit(
                '9446',
                '9446',
                'Brigade territoriale autonome de Cestas',
                '44.74221',
                '-0.68382',
                '3 Avenue du 19 Mars 1962 33610 CESTAS',
                '05 57 83 83 10',
                '$Lundi : 8h00-12h00 14h00-18h00$Mardi : 8h00-12h00 14h00-18h00$Mercredi : 8h00-12h00 14h00-18h00$Jeudi : 8h00-12h00 14h00-18h00$Vendredi : 8h00-12h00 14h00-18h00$Samedi : 8h00-12h00 14h00-18h00$Dimanche : 9h00-12h00 15h00-18h00$Jours Fériés : 9h00-12h00 15h00-18h00',
                '1004020',
                Institution::GN
            ),
            new Unit(
                '74181',
                '74181',
                'Commissariat de police d\'Arcachon',
                '44.65828',
                '-1.1609669',
                '1 Place de Verdun 33120 ARCACHON',
                '05 57 72 29 30',
                '24h/24 - 7j/7',
                '4015',
                Institution::PN
            ),
            new Unit(
                '102765',
                '102765',
                'Commissariat de police de Le Bouscat',
                '44.86629',
                '-0.595581',
                '226 Avenue de Tivoli 33110 LE BOUSCAT',
                '05 57 22 52 30',
                '24h/24 - 7j/7',
                '4028',
                Institution::PN
            ),
            new Unit(
                '102840',
                '102840',
                'COMMISSARIAT DE POLICE D\'EYSINES',
                '44.883198',
                '-0.64781326',
                '30 Avenue de Picot 33320 EYSINES',
                '05 57 93 09 30',
                '24h/24 - 7j/7',
                '4013',
                Institution::PN
            ),
            new Unit(
                '74097',
                '74097',
                'Commissariat de police de Bordeaux',
                '44.835045',
                '-0.58881265',
                '23 Rue François de Sourdis 33000 BORDEAUX',
                '05 57 85 77 77',
                '24h/24 - 7j/7',
                '4017',
                Institution::PN
            ),
        ];

        foreach ($units as $unit) {
            $manager->persist($unit);
        }

        $manager->flush();
    }
}
