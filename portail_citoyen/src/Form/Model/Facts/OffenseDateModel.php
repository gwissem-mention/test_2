<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class OffenseDateModel
{
    private bool $exactDateKnown;
    private string $choiceHour;
    private ?\DateTimeInterface $startDate = null;
    private ?\DateTimeInterface $endDate = null;
    private ?\DateTimeInterface $hour = null;
    private ?\DateTimeInterface $startHour = null;
    private ?\DateTimeInterface $endHour = null;

    public function isExactDateKnown(): bool
    {
        return $this->exactDateKnown;
    }

    public function setExactDateKnown(bool $exactDateKnown): void
    {
        $this->exactDateKnown = $exactDateKnown;
    }

    public function getChoiceHour(): string
    {
        return $this->choiceHour;
    }

    public function setChoiceHour(string $choiceHour): void
    {
        $this->choiceHour = $choiceHour;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(?\DateTimeInterface $hour): void
    {
        $this->hour = $hour;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(?\DateTimeInterface $startHour): void
    {
        $this->startHour = $startHour;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->endHour;
    }

    public function setEndHour(?\DateTimeInterface $endHour): void
    {
        $this->endHour = $endHour;
    }
}
