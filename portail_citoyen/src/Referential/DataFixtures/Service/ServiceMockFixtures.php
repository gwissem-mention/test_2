<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Service;

use App\Enum\Institution;
use App\Referential\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ServiceMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $services = [
            new Service(
                null,
                null,
                '3002739',
                'Brigade de proximité de Voiron',
                'Brigade de proximité de Voiron',
                'Voiron',
                '38',
                '630',
                Institution::GN,
                null
            ),
            new Service(
                null,
                null,
                '3026751',
                'Brigade de proximité de Renage',
                'Brigade de proximité de Renage',
                'Renage',
                '38',
                '630',
                Institution::GN,
                null
            ),
            new Service(
                null,
                null,
                '66459',
                'CSP VOIRON',
                'CSP VOIRON',
                'Voiron',
                '38',
                '630',
                Institution::PN,
                null
            ),
            new Service(
                null,
                null,
                '55016',
                'DSPAP/DTSP75/D2/11EME ARRONDISSEMENT',
                'PA75/2/11',
                '12 PASSAGE CHARLES DALLERY$75011 PARIS 11',
                '75',
                '3430 (non surtaxé)',
                Institution::PN,
                'Commissariat de police de Paris 11ème arrondissement'
            ),
        ];

        foreach ($services as $service) {
            $manager->persist($service);
        }

        $manager->flush();
    }
}
