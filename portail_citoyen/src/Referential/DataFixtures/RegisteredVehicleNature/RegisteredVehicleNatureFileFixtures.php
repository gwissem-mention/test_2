<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\RegisteredVehicleNature;

use App\Referential\Entity\RegisteredVehicleNature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class RegisteredVehicleNatureFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const CODE = 0;
    private const LABEL = 3;

    private const LENGTH = 1000;

    public function __construct(private readonly string $registeredVehicleNaturesFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->registeredVehicleNaturesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->registeredVehicleNaturesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > 1) {
                        $manager->persist(new RegisteredVehicleNature($data[self::LABEL], $data[self::CODE]));
                    }
                    ++$row;
                }
                $manager->flush();
                fclose($handle);
            }
        }
    }
}
