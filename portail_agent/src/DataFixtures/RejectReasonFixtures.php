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
            'pel.other' => RejectReason::PEL_OTHER,
            'pel.reorientation.other.solution' => RejectReason::REORIENTATION_OTHER_SOLUTION,
            'pel.drafting.victim.to.another.teleservice' => RejectReason::DRAFTING_VICTIM_TO_ANOTHER_TELESERVICE,
            'pel.drafting.a.handrail.declaration' => RejectReason::DRAFTING_A_HANDRAIL_DECLARATION,
            'pel.insufisant.quality.to.act' => RejectReason::PEL_INSUFISANT_QUALITY_TO_ACT,
            'pel.absence.of.penal.offense.object.loss' => RejectReason::ABSENCE_OF_PENAL_OFFENSE_OBJECT_LOSS,
            'pel.absence.of.penal.offense' => RejectReason::ABSENCE_OF_PENAL_OFFENSE,
            'pel.incoherent.statements' => RejectReason::INCOHERENT_STATEMENTS,
            'pel.victime.carence.at.appointment' => RejectReason::PEL_VICTIME_CARENCE_AT_APPOINTMENT,
            'pel.victime.carence.at.appointment.booking' => RejectReason::PEL_VICTIME_CARENCE_AT_APPOINTMENT_BOOKING,
            'pel.abandonment.of.the.procedure.by.victim' => RejectReason::ABANDONMENT_OF_THE_PROCEDURE_BY_VICTIM,
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
