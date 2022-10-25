<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\LocationModel;

class CivilStateModel
{
    private ?int $civility = null;
    private ?string $birthName = null;
    private ?string $usageName = null;
    private ?string $firstnames = null;
    private ?\DateTimeInterface $birthDate = null;
    private ?LocationModel $birthLocation = null;
    private ?int $nationality = null;
    private ?string $job = null;

    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility(?int $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    public function setBirthName(?string $birthName): self
    {
        $this->birthName = $birthName;

        return $this;
    }

    public function getUsageName(): ?string
    {
        return $this->usageName;
    }

    public function setUsageName(?string $usageName): self
    {
        $this->usageName = $usageName;

        return $this;
    }

    public function getFirstnames(): ?string
    {
        return $this->firstnames;
    }

    public function setFirstnames(?string $firstnames): self
    {
        $this->firstnames = $firstnames;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthLocation(): ?LocationModel
    {
        return $this->birthLocation;
    }

    public function setBirthLocation(?LocationModel $birthLocation): self
    {
        $this->birthLocation = $birthLocation;

        return $this;
    }

    public function getNationality(): ?int
    {
        return $this->nationality;
    }

    public function setNationality(?int $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }
}
