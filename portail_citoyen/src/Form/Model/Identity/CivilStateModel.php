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
    private ?int $job = null;

    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility(?int $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function civilityIsDefined(): bool
    {
        return is_int($this->civility);
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

    public function birthNameIsDefined(): bool
    {
        return is_string($this->birthName) && strlen($this->birthName) > 0;
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

    public function usageNameIsDefined(): bool
    {
        return is_string($this->usageName) && strlen($this->usageName) > 0;
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

    public function firstnamesIsDefined(): bool
    {
        return is_string($this->firstnames) && strlen($this->firstnames) > 0;
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

    public function birthDateIsDefined(): bool
    {
        return $this->birthDate instanceof \DateTimeInterface;
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

    public function birthLocationIsDefined(): bool
    {
        return $this->birthLocation instanceof LocationModel;
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

    public function getJob(): ?int
    {
        return $this->job;
    }

    public function setJob(?int $job): self
    {
        $this->job = $job;

        return $this;
    }
}
