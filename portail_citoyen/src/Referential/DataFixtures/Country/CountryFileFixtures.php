<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\Country;

use App\Referential\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CountryFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const COUNTRY_CODE = 4;
    private const COUNTRY_LABEL = 1;
    private const COUNTRY_INSEE_CODE = 0;
    private const BATCH_SIZE = 20;
    private const LENGTH = 1000;
    private const START = 1;
    private const COUNTRY_ACTUAL = 9;

    public function __construct(private readonly string $countryFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->countryFixturesPath)) {
            $row = 1;
            $handle = fopen($this->countryFixturesPath, 'rb');

            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > self::START && '1' === $data[self::COUNTRY_ACTUAL]) {
                        $country = (new Country($data[self::COUNTRY_CODE], $data[self::COUNTRY_LABEL], (int) $data[self::COUNTRY_INSEE_CODE]));
                        $manager->persist($country);
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
