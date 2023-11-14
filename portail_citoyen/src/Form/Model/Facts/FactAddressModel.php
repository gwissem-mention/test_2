<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

use App\Form\Model\Address\AbstractSerializableAddress;

class FactAddressModel
{
    private ?string $addressAdditionalInformation = null;
    private ?AbstractSerializableAddress $startAddress = null;
    private ?AbstractSerializableAddress $endAddress = null;

    public function getAddressAdditionalInformation(): ?string
    {
        return $this->addressAdditionalInformation;
    }

    public function setAddressAdditionalInformation(?string $addressAdditionalInformation): self
    {
        $this->addressAdditionalInformation = $addressAdditionalInformation;

        return $this;
    }

    public function getStartAddress(): ?AbstractSerializableAddress
    {
        return $this->startAddress;
    }

    public function setStartAddress(?AbstractSerializableAddress $startAddress): self
    {
        $this->startAddress = $startAddress;

        return $this;
    }

    public function getEndAddress(): ?AbstractSerializableAddress
    {
        return $this->endAddress;
    }

    public function setEndAddress(?AbstractSerializableAddress $endAddress): self
    {
        $this->endAddress = $endAddress;

        return $this;
    }
}
