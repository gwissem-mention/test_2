<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Job;

use App\Referential\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class JobFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const JOB_CODE = 0;
    private const JOB_LABEL = 1;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;

    public function __construct(private readonly string $jobsFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->jobsFixturesPath)) {
            $row = 1;
            $handle = fopen($this->jobsFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > 1) {
                        $manager->persist(new Job($data[self::JOB_CODE], $data[self::JOB_LABEL]));
                        if (($row % self::BATCH_SIZE) === 0) {
                            $manager->flush();
                            $manager->clear();
                        }
                    }
                    ++$row;
                }

                $manager->persist(new Job('RETR', 'Retraité'));
                $manager->persist(new Job('CHOM', 'Chômeur'));
                $manager->persist(new Job('SP', 'Sans profession'));

                $manager->flush();
                $manager->clear();
                fclose($handle);
            }
        }
    }
}