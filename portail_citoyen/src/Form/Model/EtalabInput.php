<?php

declare(strict_types=1);

namespace App\Form\Model;

class EtalabInput
{
    public function __construct(
        private string $addressSearch = '',
        private string $addressId = '',
        private string $addressSearchSaved = ''
    ) {
    }

    public function getAddressSearch(): string
    {
        return $this->addressSearch;
    }

    public function setAddressSearch(string $addressSearch): self
    {
        $this->addressSearch = $addressSearch;

        return $this;
    }

    public function getAddressId(): string
    {
        return $this->addressId;
    }

    public function setAddressId(string $addressId): self
    {
        $this->addressId = $addressId;

        return $this;
    }

    public function getAddressSearchSaved(): string
    {
        return $this->addressSearchSaved;
    }

    public function setAddressSearchSaved(string $addressSearchSaved): self
    {
        $this->addressSearchSaved = $addressSearchSaved;

        return $this;
    }
}
