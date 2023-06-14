<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\RejectReason;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RejectReasonFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['default', 'ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $rejectReasons = [
            (new RejectReason())
                ->setLabel('pel.appointment.needed')
                ->setCode('appointment-needed'),
            (new RejectReason())
                ->setLabel('pel.reorientation.other.solution')
                ->setCode('reorientation-other-solution'),
            (new RejectReason())
                ->setLabel('pel.absence.of.penal.offense')
                ->setCode('absence-of-penal-offense'),
            (new RejectReason())
                ->setLabel('pel.insufisant.quality.to.act')
                ->setCode('pel-insufisant-quality-to-act'),
            (new RejectReason())
                ->setLabel('pel.victime.carence')
                ->setCode('pel-victime-carence'),
            (new RejectReason())
                ->setLabel('pel.other')
                ->setCode('pel-other'),
        ];

        foreach ($rejectReasons as $rejectReason) {
            $manager->persist($rejectReason);
        }

        $manager->flush();
    }
}
