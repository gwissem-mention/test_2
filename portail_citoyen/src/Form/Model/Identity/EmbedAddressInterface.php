<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\AddressModel;

interface EmbedAddressInterface
{
    public function getCountry(): ?int;

    public function setCountry(?int $country): self;

    public function getFrenchAddress(): ?AddressModel;

    public function setFrenchAddress(?AddressModel $frenchAddress): self;

    public function getForeignAddress(): ?string;

    public function setForeignAddress(?string $foreignAddress): self;
}
