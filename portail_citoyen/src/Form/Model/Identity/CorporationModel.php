<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class CorporationModel
{
    private ?string $siren = null;
    private ?string $name = null;
    private ?string $function = null;
    private ?string $nationality = null;
    private ?string $email = null;
    private ?string $phone = null;
    private ?string $country = null;
    private ?string $frenchAddress = null;
    private ?string $foreignAddress = null;

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getFrenchAddress(): ?string
    {
        return $this->frenchAddress;
    }

    public function setFrenchAddress(?string $frenchAddress): self
    {
        $this->frenchAddress = $frenchAddress;

        return $this;
    }

    public function getForeignAddress(): ?string
    {
        return $this->foreignAddress;
    }

    public function setForeignAddress(?string $foreignAddress): self
    {
        $this->foreignAddress = $foreignAddress;

        return $this;
    }
}
