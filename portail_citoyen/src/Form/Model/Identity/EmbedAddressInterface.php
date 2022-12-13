<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

interface EmbedAddressInterface
{
    public function getCountry(): ?int;

    public function setCountry(?int $country): self;

    public function getFrenchAddress(): ?string;

    public function setFrenchAddress(?string $frenchAddress): self;

    public function getForeignAddress(): ?string;

    public function setForeignAddress(?string $foreignAddress): self;
}
