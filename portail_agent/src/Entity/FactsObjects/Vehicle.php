<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Vehicle extends AbstractObject
{
    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registrationNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registrationCountry = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $insuranceCompany = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $insuranceNumber = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(?string $registrationNumber): self
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getRegistrationCountry(): ?string
    {
        return $this->registrationCountry;
    }

    public function setRegistrationCountry(?string $registrationCountry): self
    {
        $this->registrationCountry = $registrationCountry;

        return $this;
    }

    public function getInsuranceCompany(): ?string
    {
        return $this->insuranceCompany;
    }

    public function setInsuranceCompany(?string $insuranceCompany): self
    {
        $this->insuranceCompany = $insuranceCompany;

        return $this;
    }

    public function getInsuranceNumber(): ?string
    {
        return $this->insuranceNumber;
    }

    public function setInsuranceNumber(?string $insuranceNumber): self
    {
        $this->insuranceNumber = $insuranceNumber;

        return $this;
    }

    public function isRegistered(): bool
    {
        if (!is_null($this->getRegistrationNumber())) {
            return true;
        }

        return false;
    }
}