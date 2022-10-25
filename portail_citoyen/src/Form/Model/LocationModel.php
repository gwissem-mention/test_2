<?php

declare(strict_types=1);

namespace App\Form\Model;

class LocationModel
{
    private ?string $country = null;
    private ?string $department = null;
    private ?string $frenchTown = null;
    private ?string $otherTown = null;

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getFrenchTown(): ?string
    {
        return $this->frenchTown;
    }

    public function setFrenchTown(?string $frenchTown): self
    {
        $this->frenchTown = $frenchTown;

        return $this;
    }

    public function getOtherTown(): ?string
    {
        return $this->otherTown;
    }

    public function setOtherTown(?string $otherTown): self
    {
        $this->otherTown = $otherTown;

        return $this;
    }
}
