<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Unit;

use App\AppEnum\Institution;
use App\Referential\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class UnitGNFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const UNIT_CODE = 1;
    private const UNIT_NAME = 2;
    private const UNIT_LATITUDE = 12;
    private const UNIT_LONGITUDE = 13;
    private const UNIT_ADDRESS = 3;
    private const UNIT_PHONE = 5;
    private const UNIT_OPENING_HOURS = 11;
    private const UNIT_ID_ANONYM = 10;
    private const UNIT_EMAIL = 4;
    private const UNIT_DEPARTMENT_EMAIL = 9;
    private const UNIT_DEPARTMENT = 6;

    private const BATCH_SIZE = 20;
    private const LENGTH = 1500;

    public function __construct(private readonly string $unitsGNFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->unitsGNFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->unitsGNFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1) {
                        $unit = new Unit(
                            $data[self::UNIT_EMAIL],
                            $data[self::UNIT_DEPARTMENT_EMAIL],
                            $data[self::UNIT_CODE],
                            $data[self::UNIT_CODE],
                            $data[self::UNIT_NAME],
                            $data[self::UNIT_LATITUDE],
                            $data[self::UNIT_LONGITUDE],
                            $data[self::UNIT_ADDRESS],
                            $data[self::UNIT_DEPARTMENT],
                            $data[self::UNIT_PHONE],
                            $data[self::UNIT_OPENING_HOURS],
                            $data[self::UNIT_ID_ANONYM],
                            Institution::GN
                        );

                        $manager->persist($unit);

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
