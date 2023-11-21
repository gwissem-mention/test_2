<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Complaint\ComplaintFixtures;
use App\Entity\AttachmentDownload;
use App\Entity\Complaint;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AttachmentDownloadFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $complaints = $manager->getRepository(Complaint::class)->findBy([], null, 6);
        $now = new \DateTimeImmutable();

        $attachmentDownloads = [
            new AttachmentDownload($complaints[0]),
            (new AttachmentDownload($complaints[1]))
                ->setDownloadedAt($now->modify('-6 days')),
            (new AttachmentDownload($complaints[2]))
                ->setDownloadedAt($now->modify('-7 days')),
            (new AttachmentDownload($complaints[3]))
                ->setDownloadedAt($now->modify('-8 days')),
            (new AttachmentDownload($complaints[4]))
                ->setDownloadedAt($now->modify('+9 days')),
            (new AttachmentDownload($complaints[5]))
                ->setDownloadedAt($now->modify('-10 days'))
                ->setCleanedAt($now->modify('-3 days')),
        ];

        foreach ($attachmentDownloads as $attachment) {
            $manager->persist($attachment);
        }

        $manager->flush();
        $manager->clear();
    }

    public function getDependencies(): array
    {
        return [
            ComplaintFixtures::class,
        ];
    }
}
