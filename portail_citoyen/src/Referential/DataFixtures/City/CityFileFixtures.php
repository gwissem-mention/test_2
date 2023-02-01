<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\City;

use App\Referential\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class CityFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const CITY_ACTUAL = 13;
    private const CITY_INSEE_CODE = 0;
    private const CITY_LABEL = 1;
    private const CITY_DEPARTMENT = 3;
    private const CITY_POST_CODE = 8;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;

    public function __construct(private readonly string $citiesFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->citiesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->citiesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1 && '1' === $data[self::CITY_ACTUAL]) {
                        $city = new City();
                        $city
                            ->setInseeCode(trim($data[self::CITY_INSEE_CODE], '"'))
                            ->setLabel(trim($data[self::CITY_LABEL], '"'))
                            ->setDepartment(trim($data[self::CITY_DEPARTMENT], '"'))
                            ->setPostCode(trim($data[self::CITY_POST_CODE], '"'));
                        $manager->persist($city);
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
