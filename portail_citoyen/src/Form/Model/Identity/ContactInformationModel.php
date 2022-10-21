<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class ContactInformationModel
{
    private string $country;
    private string $email;
    private string $mobile;
    private ?string $frenchAddress = null;
    private ?string $foreignAddress = null;

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getFrenchAddress(): ?string
    {
        return $this->frenchAddress;
    }

    public function setFrenchAddress(?string $frenchAddress): void
    {
        $this->frenchAddress = $frenchAddress;
    }

    public function getForeignAddress(): ?string
    {
        return $this->foreignAddress;
    }

    public function setForeignAddress(?string $foreignAddress): void
    {
        $this->foreignAddress = $foreignAddress;
    }
}
