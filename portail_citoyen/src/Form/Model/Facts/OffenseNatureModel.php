<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class OffenseNatureModel
{
    /**
     * @var array<int, int>
     */
    private array $offenseNatures = [];
    private ?string $aabText = null;

    /**
     * @return array<int, int>
     */
    public function getOffenseNatures(): array
    {
        return $this->offenseNatures;
    }

    /**
     * @param array<int, int> $offenseNatures
     */
    public function setOffenseNatures(array $offenseNatures): self
    {
        $this->offenseNatures = $offenseNatures;

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
