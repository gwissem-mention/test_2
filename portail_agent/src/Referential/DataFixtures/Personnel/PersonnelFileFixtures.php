<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Personnel;

use App\Referential\Entity\Personnel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PersonnelFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const PERSONNEL_ID = 0;
    private const MATRICULE = 5;
    private const SERVICE_CODE = 7;
    private const APPELLATION = 17;

    private const BATCH_SIZE = 20;
    private const LENGTH = 1500;

    public function __construct(private readonly string $personnelsFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->personnelsFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->personnelsFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1) {
                        $manager->persist(new Personnel(
                            $data[self::PERSONNEL_ID],
                            $data[self::APPELLATION],
                            $data[self::SERVICE_CODE],
                            'NULL' !== $data[self::MATRICULE] ? $data[self::MATRICULE] : null,
                        ));

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
