<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class AddressModel
{
    private bool $isAddressOrRouteFactsKnown;
    private string $addressAdditionalInformation;
    private ?string $startAddress = null;
    private ?string $endAddress = null;

    public function isAddressOrRouteFactsKnown(): bool
    {
        return $this->isAddressOrRouteFactsKnown;
    }

    public function setIsAddressOrRouteFactsKnown(bool $isAddressOrRouteFactsKnown): void
    {
        $this->isAddressOrRouteFactsKnown = $isAddressOrRouteFactsKnown;
    }

    public function getAddressAdditionalInformation(): string
    {
        return $this->addressAdditionalInformation;
    }

    public function setAddressAdditionalInformation(string $addressAdditionalInformation): void
    {
        $this->addressAdditionalInformation = $addressAdditionalInformation;
    }

    public function getStartAddress(): ?string
    {
        return $this->startAddress;
    }

    public function setStartAddress(?string $startAddress): void
    {
        $this->startAddress = $startAddress;
    }

    public function getEndAddress(): ?string
    {
        return $this->endAddress;
    }

    public function setEndAddress(?string $endAddress): void
    {
        $this->endAddress = $endAddress;
    }
}
