<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\AppEnum\Institution;
use App\Referential\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 254, nullable: true)]
    private ?string $email;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $homeDepartmentEmail;

    #[ORM\Column(length: 255, unique: true)]
    private string $code;

    #[ORM\Column(length: 150)]
    private string $name;

    #[ORM\Column(length: 150)]
    private string $shortName;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $address;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $department;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone;

    #[ORM\Column(length: 255, nullable: true, enumType: Institution::class)]
    private ?Institution $institutionCode;

    public function __construct(
        ?string $email,
        ?string $homeDepartmentEmail,
        string $code,
        string $name,
        string $shortName,
        ?string $address,
        ?string $department,
        ?string $phone,
        ?Institution $institutionCode
    ) {
        $this->email = $email;
        $this->homeDepartmentEmail = $homeDepartmentEmail;
        $this->code = $code;
        $this->name = $name;
        $this->shortName = $shortName;
        $this->address = $address;
        $this->department = $department;
        $this->phone = $phone;
        $this->institutionCode = $institutionCode;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getInstitutionCode(): ?Institution
    {
        return $this->institutionCode;
    }

    public function setInstitutionCode(?Institution $institutionCode): self
    {
        $this->institutionCode = $institutionCode;

        return $this;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getHomeDepartmentEmail(): ?string
    {
        return $this->homeDepartmentEmail;
    }

    public function setHomeDepartmentEmail(?string $homeDepartmentEmail): self
    {
        $this->homeDepartmentEmail = $homeDepartmentEmail;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }
}
