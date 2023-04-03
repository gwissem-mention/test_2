<?php

declare(strict_types=1);

namespace App\Form\Model\Address;

interface AddressInterface
{
    public function getLabel(): ?string;

    public function getLatitude(): ?string;

    public function getLongitude(): ?string;
}
