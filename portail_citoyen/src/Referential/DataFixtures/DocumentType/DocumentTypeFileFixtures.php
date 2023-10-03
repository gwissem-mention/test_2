<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\DocumentType;

use App\Referential\Entity\DocumentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class DocumentTypeFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const LABEL = 0;
    private const LENGTH = 1000;

    public function __construct(private readonly string $documentTypesFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->documentTypesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->documentTypesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > 1) {
                        $manager->persist(new DocumentType($data[self::LABEL]));
                    }
                    ++$row;
                }
                $manager->flush();
                fclose($handle);
            }
        }
    }
}
