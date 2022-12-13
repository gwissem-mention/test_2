<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

trait AddressTrait
{
    private ?int $country = null;
    private ?string $frenchAddress = null;
    private ?string $foreignAddress = null;

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getFrenchAddress(): ?string
    {
        return $this->frenchAddress;
    }

    public function setFrenchAddress(?string $frenchAddress): self
    {
        $this->frenchAddress = $frenchAddress;

        return $this;
    }

    public function getForeignAddress(): ?string
    {
        return $this->foreignAddress;
    }

    public function setForeignAddress(?string $foreignAddress): self
    {
        $this->foreignAddress = $foreignAddress;

        return $this;
    }
}
