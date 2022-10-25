<?php

declare(strict_types=1);

namespace App\Form\Model;

class ConsentModel
{
    private ?bool $agree = null;

    public function isAgree(): ?bool
    {
        return $this->agree;
    }

    public function setAgree(?bool $agree): self
    {
        $this->agree = $agree;

        return $this;
    }
}
