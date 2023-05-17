<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Department;

use App\Referential\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class DepartmentFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const DEPARTMENT_CODE = 0;
    private const DEPARTMENT_LABEL = 6;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;
    private const START = 1;

    public function __construct(private readonly string $departmentsFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->departmentsFixturesPath)) {
            $row = 1;
            $handle = fopen($this->departmentsFixturesPath, 'rb');

            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ','))) {
                    if ($row > self::START) {
                        $department = (new Department())
                            ->setCode($data[self::DEPARTMENT_CODE])
                            ->setLabel($data[self::DEPARTMENT_LABEL])
                        ;
                        $manager->persist($department);
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
