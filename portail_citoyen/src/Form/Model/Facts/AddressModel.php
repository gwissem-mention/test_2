<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class AddressModel
{
    private ?bool $isAddressOrRouteFactsKnown = null;
    private ?string $addressAdditionalInformation = null;
    private ?string $startAddress = null;
    private ?string $endAddress = null;

    public function isAddressOrRouteFactsKnown(): ?bool
    {
        return $this->isAddressOrRouteFactsKnown;
    }

    public function setIsAddressOrRouteFactsKnown(?bool $isAddressOrRouteFactsKnown): self
    {
        $this->isAddressOrRouteFactsKnown = $isAddressOrRouteFactsKnown;

        return $this;
    }

    public function getAddressAdditionalInformation(): ?string
    {
        return $this->addressAdditionalInformation;
    }

    public function setAddressAdditionalInformation(?string $addressAdditionalInformation): self
    {
        $this->addressAdditionalInformation = $addressAdditionalInformation;

        return $this;
    }

    public function getStartAddress(): ?string
    {
        return $this->startAddress;
    }

    public function setStartAddress(?string $startAddress): self
    {
        $this->startAddress = $startAddress;

        return $this;
    }

    public function getEndAddress(): ?string
    {
        return $this->endAddress;
    }

    public function setEndAddress(?string $endAddress): self
    {
        $this->endAddress = $endAddress;

        return $this;
    }
}
