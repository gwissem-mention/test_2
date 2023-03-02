<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\Address\AbstractSerializableAddress;

interface EmbedAddressInterface
{
    public function getCountry(): ?int;

    public function setCountry(?int $country): self;

    public function getFrenchAddress(): ?AbstractSerializableAddress;

    public function setFrenchAddress(?AbstractSerializableAddress $frenchAddress): self;

    public function getForeignAddress(): ?string;

    public function setForeignAddress(?string $foreignAddress): self;

    public function isSameAddress(): bool;
}
