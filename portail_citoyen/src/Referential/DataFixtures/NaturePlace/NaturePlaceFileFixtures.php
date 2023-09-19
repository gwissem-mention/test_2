<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\NaturePlace;

use App\Referential\Entity\NaturePlace;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class NaturePlaceFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const LVL1_LABEL = 0;
    private const LVL2_LABEL = 1;
    private const LABEL_THESAURUS = 2;
    private const LENGTH = 1000;
    private const START = 1;

    public function __construct(private readonly string $naturePlacesFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->naturePlacesFixturesPath)) {
            $row = 1;
            $handle = fopen($this->naturePlacesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > self::START) {
                        $naturePlaceLvl1 = $manager->getRepository(NaturePlace::class)->findOneBy(['label' => $data[self::LVL1_LABEL]]);
                        $labelThesaurus = $data[self::LABEL_THESAURUS];
                        if (!$naturePlaceLvl1 instanceof NaturePlace) {
                            $naturePlaceLvl1 = new NaturePlace(
                                label: $data[self::LVL1_LABEL],
                                labelThesaurus: empty($data[self::LVL2_LABEL]) ? $labelThesaurus : null
                            );

                            $manager->persist($naturePlaceLvl1);
                            $manager->flush();
                        }

                        if (!empty($data[self::LVL2_LABEL])) {
                            $manager->persist(new NaturePlace($data[self::LVL2_LABEL], $labelThesaurus, $naturePlaceLvl1));
                        }
                    }
                    ++$row;
                }

                $manager->flush();
                fclose($handle);
            }
        }
    }
}
