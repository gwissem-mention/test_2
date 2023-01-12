<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures;

use App\Referential\Entity\Agent;
use App\Referential\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UnitFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials'];
    }

    public function load(ObjectManager $manager): void
    {
        $unit = (new Unit())
            ->setEmail('lyon@example.com')
            ->setName('Gendarmerie de Lyon')
            ->setCode('4PWR9BBS')
            ->setAddress('Lyon')
            ->setInstitutionCode('MGJ38Y8L')
            ->setCallNumber('630')
            ->addAgent(
                (new Agent())
                    ->setFirstName('Jean')
                    ->setLastName('DUPONT')
                    ->setRank('Capitaine')
                    ->setNumber('H3U3XCGD')
                    ->setServiceCode('4PWR9BBS')
                    ->setInstitutionCode('MGJ38Y8L')
            )
            ->addAgent(
                (new Agent())
                    ->setFirstName('Thomas')
                    ->setLastName('DURAND')
                    ->setRank('Lieutenant')
                    ->setNumber('PR5KTZ9R')
                    ->setServiceCode('4PWR9BBS')
                    ->setInstitutionCode('MGJ38Y8L')
            )
            ->addAgent(
                (new Agent())
                    ->setFirstName('Alicia')
                    ->setLastName('JACQUOT')
                    ->setRank('Lieutenant')
                    ->setNumber('P7EFDYR9')
                    ->setServiceCode('4PWR9BBS')
                    ->setInstitutionCode('MGJ38Y8L')
            )
            ->addAgent(
                (new Agent())
                    ->setFirstName('Victoria')
                    ->setLastName('BARBOT')
                    ->setRank('Lieutenant')
                    ->setNumber('4A84Z9FW')
                    ->setServiceCode('4PWR9BBS')
                    ->setInstitutionCode('MGJ38Y8L')
            );

        $manager->persist($unit);
        $manager->flush();
    }
}
