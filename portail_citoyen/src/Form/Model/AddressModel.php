<?php

declare(strict_types=1);

namespace App\Form\Model;

class AddressModel
{
    private ?string $label = null;

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