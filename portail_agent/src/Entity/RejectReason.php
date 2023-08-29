<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RejectReasonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RejectReasonRepository::class)]
class RejectReason
{
    public const PEL_OTHER = 'pel-other';
    public const REORIENTATION_OTHER_SOLUTION = 'reorientation-other-solution';
    public const DRAFTING_VICTIM_TO_ANOTHER_TELESERVICE = 'drafting-victim-to-another-teleservice';
    public const DRAFTING_A_HANDRAIL_DECLARATION = 'drafting-a-handrail-declaration';
    public const PEL_INSUFISANT_QUALITY_TO_ACT = 'pel-insufisant-quality-to-act';
    public const ABSENCE_OF_PENAL_OFFENSE_OBJECT_LOSS = 'absence-of-penal-offense-object-loss';
    public const ABSENCE_OF_PENAL_OFFENSE = 'absence-of-penal-offense';
    public const PEL_VICTIME_CARENCE_AT_APPOINTMENT = 'pel-victime-carence-at-appointment';
    public const PEL_VICTIME_CARENCE_AT_APPOINTMENT_BOOKING = 'pel-victime-carence-at-appointment-booking';
    public const ABANDONMENT_OF_THE_PROCEDURE_BY_VICTIM = 'abandonment-of-the-procedure-by-victim';
    public const PRESCRIPTION_OF_FACTS = 'prescription-of-facts';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $label;

    #[ORM\Column(length: 255)]
    private string $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
