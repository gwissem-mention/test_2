<?php

declare(strict_types=1);

namespace App\Form\Model;

class ContinuationModel
{
    private ?bool $materialDamage = null;
    private ?bool $offenseAuthorKnown = null;

    public function isMaterialDamage(): ?bool
    {
        return $this->materialDamage;
    }

    public function setMaterialDamage(?bool $materialDamage): self
    {
        $this->materialDamage = $materialDamage;

        return $this;
    }

    public function isOffenseAuthorKnown(): ?bool
    {
        return $this->offenseAuthorKnown;
    }

    public function setOffenseAuthorKnown(?bool $offenseAuthorKnown): self
    {
        $this->offenseAuthorKnown = $offenseAuthorKnown;

        return $this;
    }
}
