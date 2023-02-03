<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Unit;

use App\Enum\Institution;
use App\Referential\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class UnitFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const UNIT_INSTITUTION = 6;
    private const UNIT_NAME = 22;
    private const UNIT_SHORT_NAME = 20;
    private const UNIT_EMAIL = 29;
    private const UNIT_CODE = 0;
    private const UNIT_ADDRESS = 26;
    private const UNIT_CALL_NUMBER = 27;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1500;

    public function __construct(
        private readonly string $unitsFixturesPath,
    ) {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->unitsFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->unitsFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1 && in_array($data[self::UNIT_INSTITUTION], ['1', '2'], true)) {
                        $unit = new Unit(
                            'NULL' === $data[self::UNIT_EMAIL] ? null : $data[self::UNIT_EMAIL],
                            $data[self::UNIT_CODE],
                            $data[self::UNIT_NAME],
                            $data[self::UNIT_SHORT_NAME],
                            'NULL' === $data[self::UNIT_ADDRESS] ? null : $data[self::UNIT_ADDRESS],
                            'NULL' === $data[self::UNIT_CALL_NUMBER] ? null : $data[self::UNIT_CALL_NUMBER],
                            '1' === $data[self::UNIT_INSTITUTION] ? Institution::GN : Institution::PN
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
