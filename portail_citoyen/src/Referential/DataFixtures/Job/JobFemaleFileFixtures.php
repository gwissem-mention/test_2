<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Job;

use App\Referential\Entity\Job;
use App\Referential\Repository\JobRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class JobFemaleFileFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const JOB_CODE = 0;
    private const JOB_LABEL = 1;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;
    private const START = 2;

    public function __construct(private readonly string $jobsFixturesPath, private readonly JobRepository $jobRepository)
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
                    if ($row > self::START) {
                        /** @var Job $job */
                        $job = $this->jobRepository->findOneBy(['code' => $data[self::JOB_CODE]]);
                        $job->setLabelFemale($data[self::JOB_LABEL]);
                        $manager->persist($job);
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

    public function getDependencies(): array
    {
        return [JobMaleFileFixtures::class];
    }
}
