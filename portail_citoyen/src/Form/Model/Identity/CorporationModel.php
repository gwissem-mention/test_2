<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class CorporationModel
{
    private string $siren;
    private string $name;
    private string $function;
    private string $nationality;
    private string $email;
    private string $phone;
    private string $country;
    private string $frenchAddress;
    private string $foreignAddress;

    public function getSiren(): string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): void
    {
        $this->siren = $siren;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function setFunction(string $function): void
    {
        $this->function = $function;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): void
    {
        $this->nationality = $nationality;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getFrenchAddress(): string
    {
        return $this->frenchAddress;
    }

    public function setFrenchAddress(string $frenchAddress): void
    {
        $this->frenchAddress = $frenchAddress;
    }

    public function getForeignAddress(): string
    {
        return $this->foreignAddress;
    }

    public function setForeignAddress(string $foreignAddress): void
    {
        $this->foreignAddress = $foreignAddress;
    }
}
