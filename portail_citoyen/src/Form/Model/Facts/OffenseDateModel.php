<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class OffenseDateModel
{
    private ?bool $exactDateKnown = null;
    private ?string $choiceHour = null;
    private ?\DateTimeInterface $startDate = null;
    private ?\DateTimeInterface $endDate = null;
    private ?\DateTimeInterface $hour = null;
    private ?\DateTimeInterface $startHour = null;
    private ?\DateTimeInterface $endHour = null;

    public function isExactDateKnown(): ?bool
    {
        return $this->exactDateKnown;
    }

    public function setExactDateKnown(?bool $exactDateKnown): self
    {
        $this->exactDateKnown = $exactDateKnown;

        return $this;
    }

    public function getChoiceHour(): ?string
    {
        return $this->choiceHour;
    }

    public function setChoiceHour(?string $choiceHour): self
    {
        $this->choiceHour = $choiceHour;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(?\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(?\DateTimeInterface $startHour): self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->endHour;
    }

    public function setEndHour(?\DateTimeInterface $endHour): self
    {
        $this->endHour = $endHour;

        return $this;
    }
}
