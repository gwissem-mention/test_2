<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Service;

use App\Enum\Institution;
use App\Referential\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class ServiceFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const SERVICE_INSTITUTION = 6;
    private const SERVICE_NAME = 22;
    private const SERVICE_SHORT_NAME = 20;
    private const SERVICE_PUBLIC_NAME = 23;
    private const SERVICE_EMAIL = 29;
    private const SERVICE_EMAIL_HOME_DEPARTMENT = 38;
    private const SERVICE_CODE = 0;
    private const SERVICE_ADDRESS = 26;
    private const SERVICE_DEPARTMENT = 12;
    private const SERVICE_CALL_NUMBER = 27;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1500;

    public function __construct(
        private readonly string $servicesFixturesPath,
    ) {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->servicesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->servicesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH))) {
                    if ($row > 1 && in_array($data[self::SERVICE_INSTITUTION], ['1', '2', '3'], true)) {
                        $service = new Service(
                            'NULL' === $data[self::SERVICE_EMAIL] ? null : $data[self::SERVICE_EMAIL],
                            'NULL' === $data[self::SERVICE_EMAIL_HOME_DEPARTMENT] ? null : $data[self::SERVICE_EMAIL_HOME_DEPARTMENT],
                            $data[self::SERVICE_CODE],
                            $data[self::SERVICE_NAME],
                            $data[self::SERVICE_SHORT_NAME],
                            'NULL' === $data[self::SERVICE_ADDRESS] ? null : $data[self::SERVICE_ADDRESS],
                            'NULL' === $data[self::SERVICE_DEPARTMENT] ? null : $data[self::SERVICE_DEPARTMENT],
                            'NULL' === $data[self::SERVICE_CALL_NUMBER] ? null : $data[self::SERVICE_CALL_NUMBER],
                            '1' === $data[self::SERVICE_INSTITUTION] ? Institution::GN : Institution::PN,
                            'NULL' === $data[self::SERVICE_PUBLIC_NAME] ? null : $data[self::SERVICE_PUBLIC_NAME],
                        );

                        $manager->persist($service);

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
