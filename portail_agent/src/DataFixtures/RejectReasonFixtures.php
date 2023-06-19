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
            'pel.other' => 'pel-other',
            'pel.reorientation.other.solution' => 'reorientation-other-solution',
            'pel.drafting.victim.to.another.teleservice' => 'drafting-victim-to-another-teleservice',
            'pel.drafting.a.handrail.declaration' => 'drafting-a-handrail-declaration',
            'pel.insufisant.quality.to.act' => 'pel-insufisant-quality-to-act',
            'pel.absence.of.penal.offense.object.loss' => 'absence-of-penal-offense-object-loss',
            'pel.absence.of.penal.offense' => 'absence-of-penal-offense',
            'pel.incoherent.statements' => 'incoherent-statements',
            'pel.victime.carence.at.appointment' => 'pel-victime-carence-at-appointment',
            'pel.victime.carence.at.appointment.booking' => 'pel-victime-carence-at-appointment-booking',
            'pel.abandonment.of.the.procedure.by.victim' => 'abandonment-of-the-procedure-by-victim',
        ];

        foreach ($rejectReasons as $label => $code) {
            $rejectReason = new RejectReason();
            $rejectReason
                ->setLabel($label)
                ->setCode($code);

            $manager->persist($rejectReason);
        }

        $manager->flush();
    }
}
