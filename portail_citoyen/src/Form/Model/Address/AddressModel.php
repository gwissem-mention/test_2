<?php

declare(strict_types=1);

namespace App\Form\Model\Address;

class AddressModel extends AbstractSerializableAddress
{
    use LatLngTrait;

    private ?string $label = null;

    public function __construct()
    {
        $this->addressType = 'address';
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }
}
