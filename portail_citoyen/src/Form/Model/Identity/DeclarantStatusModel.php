<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class DeclarantStatusModel
{
    private ?int $declarantStatus = null;

    public function getDeclarantStatus(): ?int
    {
        return $this->declarantStatus;
    }

    public function setDeclarantStatus(?int $declarantStatus): self
    {
        $this->declarantStatus = $declarantStatus;

        return $this;
    }
}
