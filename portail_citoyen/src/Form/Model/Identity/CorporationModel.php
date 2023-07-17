<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class CorporationModel implements EmbedAddressInterface
{
    use AddressTrait;
    use EmailTrait;

    private ?string $siret = null;
    private ?string $name = null;
    private ?string $function = null;
    private ?string $nationality = null;
    private ?PhoneModel $phone = null;

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(?string $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getPhone(): ?PhoneModel
    {
        return $this->phone;
    }

    public function setPhone(?PhoneModel $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
