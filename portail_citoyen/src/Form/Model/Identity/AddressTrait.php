<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\Address\AbstractSerializableAddress;
use App\Form\Model\Address\AddressForeignModel;

trait AddressTrait
{
    private bool $sameAddress = false;
    private ?int $country = null;
    private ?AbstractSerializableAddress $frenchAddress = null;
    private ?AddressForeignModel $foreignAddress = null;

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getFrenchAddress(): ?AbstractSerializableAddress
    {
        return $this->frenchAddress;
    }

    public function setFrenchAddress(?AbstractSerializableAddress $frenchAddress): self
    {
        $this->frenchAddress = $frenchAddress;

        return $this;
    }

    public function getForeignAddress(): ?AddressForeignModel
    {
        return $this->foreignAddress;
    }

    public function setForeignAddress(?AddressForeignModel $foreignAddress): self
    {
        $this->foreignAddress = $foreignAddress;

        return $this;
    }

    public function isSameAddress(): bool
    {
        return $this->sameAddress;
    }

    public function setSameAddress(bool $sameAddress): self
    {
        $this->sameAddress = $sameAddress;

        return $this;
    }
}
