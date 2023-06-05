<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Nationality;

use App\Referential\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class NationalityFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const CODE = 6;
    private const LABEL = 5;

    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;

    public function __construct(private readonly string $nationalitiesFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->nationalitiesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->nationalitiesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > 1) {
                        $nationality = new Nationality();
                        $nationality
                            ->setCode(trim($data[self::CODE], ' "'))
                            ->setLabel(trim($data[self::LABEL], ' "'));

                        $manager->persist($nationality);

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
