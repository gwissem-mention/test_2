<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\CityService;

use App\Referential\Entity\CityService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class CityServiceFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const CITY_CODE = 0;
    private const SERVICE_CODE = 1;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;

    public function __construct(
        private readonly string $citiesServicesFixturesPath,
    ) {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->citiesServicesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->citiesServicesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1) {
                        $cityService = new CityService($data[self::CITY_CODE], $data[self::SERVICE_CODE]);
                        $manager->persist($cityService);

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
