<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class MultimediaObject extends AbstractObject
{
    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $nature = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $operator = null;

    //    #[ORM\Column(nullable: true)]
    //    private ?bool $opposition = null;

    //    #[ORM\Column(length: 255, nullable: true)]
    //    private ?string $simNumber = null;

    #[ORM\Column(nullable: true)]
    private ?string $serialNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(nullable: true)]
    private ?bool $stillOnWhenMobileStolen = null;

    #[ORM\Column(nullable: true)]
    private ?bool $keyboardLockedWhenMobileStolen = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pinEnabledWhenMobileStolen = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mobileInsured = null;

    #[ORM\Column(nullable: false)]
    private ?bool $owned = true;

    #[ORM\Column(nullable: true)]
    private ?string $ownerLastname = null;

    #[ORM\Column(nullable: true)]
    private ?string $ownerFirstname = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
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

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setOperator(?string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function isStillOnWhenMobileStolen(): ?bool
    {
        return $this->stillOnWhenMobileStolen;
    }

    public function setStillOnWhenMobileStolen(?bool $stillOnWhenMobileStolen): self
    {
        $this->stillOnWhenMobileStolen = $stillOnWhenMobileStolen;

        return $this;
    }

    public function isKeyboardLockedWhenMobileStolen(): ?bool
    {
        return $this->keyboardLockedWhenMobileStolen;
    }

    public function setKeyboardLockedWhenMobileStolen(?bool $keyboardLockedWhenMobileStolen): self
    {
        $this->keyboardLockedWhenMobileStolen = $keyboardLockedWhenMobileStolen;

        return $this;
    }

    public function isPinEnabledWhenMobileStolen(): ?bool
    {
        return $this->pinEnabledWhenMobileStolen;
    }

    public function setPinEnabledWhenMobileStolen(?bool $pinEnabledWhenMobileStolen): self
    {
        $this->pinEnabledWhenMobileStolen = $pinEnabledWhenMobileStolen;

        return $this;
    }

    public function isMobileInsured(): ?bool
    {
        return $this->mobileInsured;
    }

    public function setMobileInsured(?bool $mobileInsured): self
    {
        $this->mobileInsured = $mobileInsured;

        return $this;
    }

    public function isOwned(): ?bool
    {
        return $this->owned;
    }

    public function setOwned(?bool $owned): self
    {
        $this->owned = $owned;

        return $this;
    }

    public function getOwnerLastname(): ?string
    {
        return $this->ownerLastname;
    }

    public function setOwnerLastname(?string $ownerLastname): self
    {
        $this->ownerLastname = $ownerLastname;

        return $this;
    }

    public function getOwnerFirstname(): ?string
    {
        return $this->ownerFirstname;
    }

    public function setOwnerFirstname(?string $ownerFirstname): self
    {
        $this->ownerFirstname = $ownerFirstname;

        return $this;
    }
}
