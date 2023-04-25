<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\CityUnit;

use App\Referential\Entity\CityUnit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class CityUnitFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const CITY_CODE = 0;
    private const UNIT_CODE = 1;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;

    public function __construct(
        private readonly string $citiesUnitsFixturesPath,
    ) {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->citiesUnitsFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->citiesUnitsFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1) {
                        $cityUnit = new CityUnit($data[self::CITY_CODE], $data[self::UNIT_CODE]);
                        $manager->persist($cityUnit);

                        if (($row % self::BATCH_SIZE) === 0) {
                            $manager->flush();
                            $manager->clear();
                        }
                    }
                    ++$row;
                }
                $manager->flush();
                $manager->clear();
                fclose($handle);
            }
        }
    }
}
