<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Department;

use App\Referential\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class DepartmentMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $departments = [
            (new Department())
                ->setCode('75')
                ->setLabel('Paris'),
            (new Department())
                ->setCode('69')
                ->setLabel('Rhône'),
        ];

        foreach ($departments as $department) {
            $manager->persist($department);
        }

        $manager->flush();
    }
}
