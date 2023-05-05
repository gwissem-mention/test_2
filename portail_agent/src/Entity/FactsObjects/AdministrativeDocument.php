<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AdministrativeDocument extends AbstractObject
{
    //    #[ORM\Column(length: 255)]
    //    private ?string $issuingCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    //    #[ORM\Column(length: 255)]
    //    private ?string $number = null;
    //
    //    #[ORM\Column]
    //    private ?\DateTimeImmutable $deliveryDate = null;
    //
    //    #[ORM\Column(length: 255)]
    //    private ?string $authority = null;
    //
    //    #[ORM\Column(length: 255)]
    //    private ?string $description = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
