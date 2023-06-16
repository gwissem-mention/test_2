<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Identity
{
    use AlertTrait;
    public const CIVILITY_MALE = 1;
    public const CIVILITY_FEMALE = 2;

    public const DECLARANT_STATUS_VICTIM = 1;
    public const DECLARANT_STATUS_PERSON_LEGAL_REPRESENTATIVE = 2;
    public const DECLARANT_STATUS_CORPORATION_LEGAL_REPRESENTATIVE = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $declarantStatus = null;

    //    #[ORM\Column(length: 255, nullable: true)]
    //    private ?string $relationshipWithVictim = null;

    #[ORM\Column(length: 255)]
    private ?int $civility = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marriedName = null;

    #[ORM\Column(length: 255)]
    private ?string $familySituation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 255)]
    private ?string $birthCity = null;

    #[ORM\Column(length: 255)]
    private ?string $birthPostalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $birthInseeCode = null;

    #[ORM\Column(length: 255)]
    private ?string $birthCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $birthDepartment = null;

    #[ORM\Column]
    private ?int $birthDepartmentNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $addressStreetNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $addressStreetType = null;

    #[ORM\Column(length: 255)]
    private ?string $addressStreetName = null;

    #[ORM\Column(length: 255)]
    private ?string $addressCity = null;

    #[ORM\Column(length: 255)]
    private ?string $addressInseeCode = null;

    #[ORM\Column(length: 255)]
    private ?string $addressPostcode = null;

    #[ORM\Column(length: 255)]
    private ?string $addressCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $addressDepartment = null;

    #[ORM\Column(nullable: true)]
    private ?int $addressDepartmentNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mobilePhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $homePhone = null;

    //    #[ORM\Column(length: 255)]
    //    private ?string $officePhone = null;

    #[ORM\Column(length: 254)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $nationality = null;

    #[ORM\Column(length: 255)]
    private ?string $job = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeclarantStatus(): ?int
    {
        return $this->declarantStatus;
    }

    public function setDeclarantStatus(?int $declarantStatus): self
    {
        $this->declarantStatus = $declarantStatus;

        return $this;
    }

    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility(int $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFamilySituation(): ?string
    {
        return $this->familySituation;
    }

    public function setFamilySituation(?string $familySituation): self
    {
        $this->familySituation = $familySituation;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthCountry(): ?string
    {
        return $this->birthCountry;
    }

    public function getBirthCity(): ?string
    {
        return $this->birthCity;
    }

    public function setBirthCity(?string $birthCity): self
    {
        $this->birthCity = $birthCity;

        return $this;
    }

    public function setBirthCountry(string $birthCountry): self
    {
        $this->birthCountry = $birthCountry;

        return $this;
    }

    public function getBirthDepartment(): ?string
    {
        return $this->birthDepartment;
    }

    public function setBirthDepartment(string $birthDepartment): self
    {
        $this->birthDepartment = $birthDepartment;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->addressCity;
    }

    public function setAddressCity(string $addressCity): self
    {
        $this->addressCity = $addressCity;

        return $this;
    }

    public function getAddressPostcode(): ?string
    {
        return $this->addressPostcode;
    }

    public function setAddressPostcode(string $addressPostcode): self
    {
        $this->addressPostcode = $addressPostcode;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function getHomePhone(): ?string
    {
        return $this->homePhone;
    }

    public function setHomePhone(?string $homePhone): self
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getMarriedName(): ?string
    {
        return $this->marriedName;
    }

    public function setMarriedName(?string $marriedName): self
    {
        $this->marriedName = $marriedName;

        return $this;
    }

    public function getBirthPostalCode(): ?string
    {
        return $this->birthPostalCode;
    }

    public function setBirthPostalCode(?string $birthPostalCode): self
    {
        $this->birthPostalCode = $birthPostalCode;

        return $this;
    }

    public function getBirthInseeCode(): ?string
    {
        return $this->birthInseeCode;
    }

    public function setBirthInseeCode(?string $birthInseeCode): self
    {
        $this->birthInseeCode = $birthInseeCode;

        return $this;
    }

    public function getBirthDepartmentNumber(): ?int
    {
        return $this->birthDepartmentNumber;
    }

    public function setBirthDepartmentNumber(?int $birthDepartmentNumber): self
    {
        $this->birthDepartmentNumber = $birthDepartmentNumber;

        return $this;
    }

    public function getAddressStreetNumber(): ?string
    {
        return $this->addressStreetNumber;
    }

    public function setAddressStreetNumber(?string $addressStreetNumber): self
    {
        $this->addressStreetNumber = $addressStreetNumber;

        return $this;
    }

    public function getAddressStreetType(): ?string
    {
        return $this->addressStreetType;
    }

    public function setAddressStreetType(?string $addressStreetType): self
    {
        $this->addressStreetType = $addressStreetType;

        return $this;
    }

    public function getAddressStreetName(): ?string
    {
        return $this->addressStreetName;
    }

    public function setAddressStreetName(?string $addressStreetName): self
    {
        $this->addressStreetName = $addressStreetName;

        return $this;
    }

    public function getAddressInseeCode(): ?string
    {
        return $this->addressInseeCode;
    }

    public function setAddressInseeCode(?string $addressInseeCode): self
    {
        $this->addressInseeCode = $addressInseeCode;

        return $this;
    }

    public function getAddressCountry(): ?string
    {
        return $this->addressCountry;
    }

    public function setAddressCountry(?string $addressCountry): self
    {
        $this->addressCountry = $addressCountry;

        return $this;
    }

    public function getAddressDepartment(): ?string
    {
        return $this->addressDepartment;
    }

    public function setAddressDepartment(?string $addressDepartment): self
    {
        $this->addressDepartment = $addressDepartment;

        return $this;
    }

    public function getAddressDepartmentNumber(): ?int
    {
        return $this->addressDepartmentNumber;
    }

    public function setAddressDepartmentNumber(?int $addressDepartmentNumber): self
    {
        $this->addressDepartmentNumber = $addressDepartmentNumber;

        return $this;
    }
}
