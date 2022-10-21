<?php

declare(strict_types=1);

namespace App\Form\Model;

class LocationModel
{
    private string $country;
    private ?string $department = null;
    private ?string $frenchTown = null;
    private ?string $otherTown = null;

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): void
    {
        $this->department = $department;
    }

    public function getFrenchTown(): ?string
    {
        return $this->frenchTown;
    }

    public function setFrenchTown(?string $frenchTown): void
    {
        $this->frenchTown = $frenchTown;
    }

    public function getOtherTown(): ?string
    {
        return $this->otherTown;
    }

    public function setOtherTown(?string $otherTown): void
    {
        $this->otherTown = $otherTown;
    }
}
