<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Complaint;
use App\Entity\User;
use App\Factory\NotificationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NotificationFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function __construct(private readonly NotificationFactory $notificationFactory)
    {
    }

    public static function getGroups(): array
    {
        return ['default'];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $manager->getRepository(User::class)->findOneBy([]);

        /** @var array<Complaint> $complaints */
        $complaints = $manager->getRepository(Complaint::class)->findBy([], [], 2);

        foreach ($complaints as $complaint) {
            $manager->persist(
                $user->addNotification($this->notificationFactory->createForComplaintAssigned($complaint))
            );
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ComplaintFixtures::class,
        ];
    }
}
