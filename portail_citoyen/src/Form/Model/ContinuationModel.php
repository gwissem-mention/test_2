<?php

declare(strict_types=1);

namespace App\Form\Model;

class ContinuationModel
{
    private bool $materialDamage;
    private ?bool $offenseAuthorKnown = null;

    public function isMaterialDamage(): bool
    {
        return $this->materialDamage;
    }

    public function setMaterialDamage(bool $materialDamage): void
    {
        $this->materialDamage = $materialDamage;
    }

    public function isOffenseAuthorKnown(): ?bool
    {
        return $this->offenseAuthorKnown;
    }

    public function setOffenseAuthorKnown(?bool $offenseAuthorKnown): void
    {
        $this->offenseAuthorKnown = $offenseAuthorKnown;
    }
}
