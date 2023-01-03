<?php

declare(strict_types=1);

namespace App\Form\Model;

class LocationModel
{
    private ?int $country = null;
    private ?string $frenchTown = null;
    private ?string $otherTown = null;

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;

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
