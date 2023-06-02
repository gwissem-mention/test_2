<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AdministrativeDocument extends AbstractObject
{
    //    #[ORM\Column(length: 255)]
    //    private ?string $issuingCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?bool $owned = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerLastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerFirstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerCompany = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressStreetType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressStreetNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressStreetName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressInseeCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressPostcode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressCity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressDepartmentNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ownerAddressDepartment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $issuedBy = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $issuedOn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $validityEndDate = null;

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

    public function isOwned(): ?bool
    {
        return $this->owned;
    }

    public function setOwned(bool $owned): self
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

    public function getOwnerCompany(): ?string
    {
        return $this->ownerCompany;
    }

    public function setOwnerCompany(?string $ownerCompany): self
    {
        $this->ownerCompany = $ownerCompany;

        return $this;
    }

    public function getOwnerPhone(): ?string
    {
        return $this->ownerPhone;
    }

    public function setOwnerPhone(?string $ownerPhone): self
    {
        $this->ownerPhone = $ownerPhone;

        return $this;
    }

    public function getOwnerEmail(): ?string
    {
        return $this->ownerEmail;
    }

    public function setOwnerEmail(?string $ownerEmail): self
    {
        $this->ownerEmail = $ownerEmail;

        return $this;
    }

    public function getOwnerAddress(): ?string
    {
        return $this->ownerAddress;
    }

    public function setOwnerAddress(?string $ownerAddress): self
    {
        $this->ownerAddress = $ownerAddress;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getIssuedBy(): ?string
    {
        return $this->issuedBy;
    }

    public function setIssuedBy(?string $issuedBy): self
    {
        $this->issuedBy = $issuedBy;

        return $this;
    }

    public function getIssuedOn(): ?\DateTimeInterface
    {
        return $this->issuedOn;
    }

    public function setIssuedOn(?\DateTimeInterface $issuedOn): self
    {
        $this->issuedOn = $issuedOn;

        return $this;
    }

    public function getValidityEndDate(): ?\DateTimeInterface
    {
        return $this->validityEndDate;
    }

    public function setValidityEndDate(?\DateTimeInterface $validityEndDate): self
    {
        $this->validityEndDate = $validityEndDate;

        return $this;
    }

    public function getOwnerAddressStreetType(): ?string
    {
        return $this->ownerAddressStreetType;
    }

    public function setOwnerAddressStreetType(?string $ownerAddressStreetType): self
    {
        $this->ownerAddressStreetType = $ownerAddressStreetType;

        return $this;
    }

    public function getOwnerAddressStreetNumber(): ?string
    {
        return $this->ownerAddressStreetNumber;
    }

    public function setOwnerAddressStreetNumber(?string $ownerAddressStreetNumber): self
    {
        $this->ownerAddressStreetNumber = $ownerAddressStreetNumber;

        return $this;
    }

    public function getOwnerAddressStreetName(): ?string
    {
        return $this->ownerAddressStreetName;
    }

    public function setOwnerAddressStreetName(?string $ownerAddressStreetName): self
    {
        $this->ownerAddressStreetName = $ownerAddressStreetName;

        return $this;
    }

    public function getOwnerAddressInseeCode(): ?string
    {
        return $this->ownerAddressInseeCode;
    }

    public function setOwnerAddressInseeCode(?string $ownerAddressInseeCode): self
    {
        $this->ownerAddressInseeCode = $ownerAddressInseeCode;

        return $this;
    }

    public function getOwnerAddressPostcode(): ?string
    {
        return $this->ownerAddressPostcode;
    }

    public function setOwnerAddressPostcode(?string $ownerAddressPostcode): self
    {
        $this->ownerAddressPostcode = $ownerAddressPostcode;

        return $this;
    }

    public function getOwnerAddressCity(): ?string
    {
        return $this->ownerAddressCity;
    }

    public function setOwnerAddressCity(?string $ownerAddressCity): self
    {
        $this->ownerAddressCity = $ownerAddressCity;

        return $this;
    }

    public function getOwnerAddressDepartmentNumber(): ?string
    {
        return $this->ownerAddressDepartmentNumber;
    }

    public function setOwnerAddressDepartmentNumber(?string $ownerAddressDepartmentNumber): self
    {
        $this->ownerAddressDepartmentNumber = $ownerAddressDepartmentNumber;

        return $this;
    }

    public function getOwnerAddressDepartment(): ?string
    {
        return $this->ownerAddressDepartment;
    }

    public function setOwnerAddressDepartment(?string $ownerAddressDepartment): self
    {
        $this->ownerAddressDepartment = $ownerAddressDepartment;

        return $this;
    }
}
