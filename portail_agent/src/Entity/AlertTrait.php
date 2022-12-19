<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait AlertTrait
{
    #[ORM\Column(nullable: true)]
    private ?int $alertNumber = null;

    public function getAlertNumber(): ?int
    {
        return $this->alertNumber;
    }

    public function setAlertNumber(?int $alertNumber): self
    {
        $this->alertNumber = $alertNumber;

        return $this;
    }
}
