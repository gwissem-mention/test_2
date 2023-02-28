<?php

namespace App\Entity\FactsObjects;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PaymentMethod extends AbstractObject
{
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    // #[ORM\Column(length: 255)]
    // private ?string $currency = null;
    //
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    // #[ORM\Column(nullable: true)]
    // private ?bool $opposition = null;
    //
    #[ORM\Column(length: 255)]
    private ?string $bank = null;
    //
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $cardType = null;
    //
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $chequeNumber = null;
    //
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $firstChequeNumber = null;
    //
    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $lastChequeNumber = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(?string $bank): self
    {
        $this->bank = $bank;

        return $this;
    }
}
