<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\Institution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['default'];
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            (new User('H3U3XCGD', Institution::PN))
                ->setServiceCode('103131') // CSP VOIRON/SVP/UPS/BRIGADE DE JOUR
                ->setAppellation('Jean DUPONT'),
            (new User('PR5KTZ9R', Institution::GN))
                ->setServiceCode('3002739') // Brigade de proximité de Voiron
                ->setAppellation('Thomas DURAND'),
        ];

        foreach ($users as $user) {
            $manager->persist($user);
        }

        $manager->flush();
    }
}
