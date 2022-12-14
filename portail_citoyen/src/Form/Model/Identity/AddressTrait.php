<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\AddressModel;

trait AddressTrait
{
    private ?int $country = null;
    private ?AddressModel $frenchAddress = null;
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

    public function getFrenchAddress(): ?AddressModel
    {
        return $this->frenchAddress;
    }

    public function setFrenchAddress(?AddressModel $frenchAddress): self
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
