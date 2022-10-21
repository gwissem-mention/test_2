<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class OffenseNatureModel
{
    private int $offenseNature;
    private ?string $aabText;

    public function getOffenseNature(): int
    {
        return $this->offenseNature;
    }

    public function setOffenseNature(int $offenseNature): void
    {
        $this->offenseNature = $offenseNature;
    }

    public function getAabText(): ?string
    {
        return $this->aabText;
    }

    public function setAabText(?string $aabText): void
    {
        $this->aabText = $aabText;
    }
}
