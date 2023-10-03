<?php

declare(strict_types=1);

namespace App\Referential\DataFixtures\PaymentCategory;

use App\Referential\Entity\PaymentCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PaymentCategoriesMockFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['referentials-ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $paymentCategories = [
            (new PaymentCategory())
                ->setLabel('chequier')
                ->setCode('00001'),
            (new PaymentCategory())
                ->setLabel('cheque restaurant')
                ->setCode('00002'),
            (new PaymentCategory())
                ->setLabel('cheque vacances')
                ->setCode('00003'),
            (new PaymentCategory())
                ->setLabel('carte bancaire')
                ->setCode('00004'),
        ];

        foreach ($paymentCategories as $paymentCategory) {
            $manager->persist($paymentCategory);
        }

        $manager->flush();
    }
}
