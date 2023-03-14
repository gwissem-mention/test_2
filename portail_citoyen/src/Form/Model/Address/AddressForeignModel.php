<?php

declare(strict_types=1);

namespace App\Form\Model\Address;

final class AddressForeignModel extends AddressEtalabModel
{
    private ?string $apartment = null;

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function setApartment(?string $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getHouseNumber().' '.$this->getType().' '.$this->getStreet().' '.$this->getApartment().' '
            .$this->getCity().' '.$this->getContext().' '.$this->getPostcode();
    }
}
