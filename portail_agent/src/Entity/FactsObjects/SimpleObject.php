<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class SimpleObject extends AbstractObject
{
    #[ORM\Column(length: 255)]
    private ?string $nature = null;

//    #[ORM\Column(length: 255)]
//    private ?string $brand = null;
//
//    #[ORM\Column(length: 255)]
//    private ?string $model = null;
//
//    #[ORM\Column(length: 255)]
//    private ?string $serialNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
