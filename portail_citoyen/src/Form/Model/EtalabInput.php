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

    public function setAddressSearch(string $addressSearch): void
    {
        $this->addressSearch = $addressSearch;
    }

    public function getAddressId(): string
    {
        return $this->addressId;
    }

    public function setAddressId(string $addressId): void
    {
        $this->addressId = $addressId;
    }

    public function getAddressSearchSaved(): string
    {
        return $this->addressSearchSaved;
    }

    public function setAddressSearchSaved(string $addressSearchSaved): void
    {
        $this->addressSearchSaved = $addressSearchSaved;
    }
}
