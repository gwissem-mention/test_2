<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class OffenseNatureModel
{
    private ?int $offenseNature = null;
    private ?string $aabText = null;

    public function getOffenseNature(): ?int
    {
        return $this->offenseNature;
    }

    public function setOffenseNature(?int $offenseNature): self
    {
        $this->offenseNature = $offenseNature;

        return $this;
    }

    public function getAabText(): ?string
    {
        return $this->aabText;
    }

    public function setAabText(?string $aabText): self
    {
        $this->aabText = $aabText;

        return $this;
    }
}
