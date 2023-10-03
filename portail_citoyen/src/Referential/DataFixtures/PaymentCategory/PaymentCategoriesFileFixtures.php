<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\PaymentCategory;

use App\Referential\Entity\PaymentCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentCategoriesFileFixtures extends Fixture implements FixtureGroupInterface
{
    private const CODE = 0;
    private const LABEL = 3;
    private const LENGTH = 1000;

    public function __construct(private readonly string $paymentCategoriesFixturesPath)
    {
    }

    public static function getGroups(): array
    {
        return ['referentials-prod'];
    }

    public function load(ObjectManager $manager): void
    {
        if (file_exists($this->paymentCategoriesFixturesPath)) {
            if ($manager instanceof EntityManagerInterface) {
                $manager->getConnection()->getConfiguration()->setSQLLogger(null);
            }
            $row = 1;
            $handle = fopen($this->paymentCategoriesFixturesPath, 'rb');
            if (is_resource($handle)) {
                while (is_array($data = fgetcsv($handle, self::LENGTH, ';'))) {
                    if ($row > 1) {
                        $paymentWaysCategory = new PaymentCategory();
                        $paymentWaysCategory
                            ->setCode(trim($data[self::CODE], ' "'))
                            ->setLabel(trim($data[self::LABEL], ' "'));

                        $manager->persist($paymentWaysCategory);
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
