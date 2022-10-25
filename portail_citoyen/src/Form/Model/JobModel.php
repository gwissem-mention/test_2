<?php

declare(strict_types=1);

namespace App\Form\Model;

class JobModel
{
    private ?int $job = null;

    public function isJob(): ?int
    {
        return $this->job;
    }

    public function setJob(?int $job): self
    {
        $this->job = $job;

        return $this;
    }
}
