<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserCiFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $users = UserFixtures::getUsers();

        foreach ($users as $user) {
            $manager->persist((new User($user['number'], $user['institution'], $user['roles']))
                ->setAppellation($user['appellation'])
                ->setServiceCode($user['serviceCode'])
                ->setTimezone('UTC')
            );
        }

        $manager->flush();
    }
}
