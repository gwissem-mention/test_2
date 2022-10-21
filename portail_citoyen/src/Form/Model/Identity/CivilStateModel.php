<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\LocationModel;

class CivilStateModel
{
    private int $civility;
    private string $birthName;
    private string $usageName;
    private string $firstnames;
    private ?\DateTimeInterface $birthDate = null;
    private LocationModel $birthLocation;
    private int $nationality;
    private ?string $job = null;

    public function getCivility(): int
    {
        return $this->civility;
    }

    public function setCivility(int $civility): void
    {
        $this->civility = $civility;
    }

    public function getBirthName(): string
    {
        return $this->birthName;
    }

    public function setBirthName(string $birthName): void
    {
        $this->birthName = $birthName;
    }

    public function getUsageName(): string
    {
        return $this->usageName;
    }

    public function setUsageName(string $usageName): void
    {
        $this->usageName = $usageName;
    }

    public function getFirstnames(): string
    {
        return $this->firstnames;
    }

    public function setFirstnames(string $firstnames): void
    {
        $this->firstnames = $firstnames;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getBirthLocation(): LocationModel
    {
        return $this->birthLocation;
    }

    public function setBirthLocation(LocationModel $birthLocation): void
    {
        $this->birthLocation = $birthLocation;
    }

    public function getNationality(): int
    {
        return $this->nationality;
    }

    public function setNationality(int $nationality): void
    {
        $this->nationality = $nationality;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): void
    {
        $this->job = $job;
    }
}
