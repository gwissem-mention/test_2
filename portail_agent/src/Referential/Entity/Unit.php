<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\AppEnum\Institution;
use App\Referential\Repository\UnitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[Ignore]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Ignore]
    #[ORM\Column(type: Types::STRING)]
    private string $serviceId;

    #[ORM\Column(type: Types::STRING)]
    private string $code;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[Groups(['appointment_map'])]
    #[ORM\Column(type: Types::STRING)]
    private string $latitude;

    #[Groups(['appointment_map'])]
    #[ORM\Column(type: Types::STRING)]
    private string $longitude;

    #[ORM\Column(type: Types::STRING)]
    private string $address;

    #[ORM\Column(type: Types::STRING)]
    private string $phone;

    #[ORM\Column(type: Types::TEXT)]
    private string $openingHours;

    #[Groups(['appointment_map'])]
    #[ORM\Column(type: Types::STRING)]
    private string $idAnonym;

    #[Ignore]
    #[ORM\Column(length: 255, nullable: true, enumType: Institution::class)]
    private ?Institution $institutionCode;

    #[ORM\Column(length: 254)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $homeDepartmentEmail;

    #[ORM\Column(length: 255)]
    private string $department;

    public function __construct(
        string $email,
        string $homeDepartmentEmail,
        string $serviceId,
        string $code,
        string $name,
        string $latitude,
        string $longitude,
        string $address,
        string $department,
        string $phone,
        string $openingHours,
        string $idAnonym,
        ?Institution $institutionCode
    ) {
        $this->email = $email;
        $this->homeDepartmentEmail = $homeDepartmentEmail;
        $this->serviceId = $serviceId;
        $this->code = $code;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->address = $address;
        $this->phone = $phone;
        $this->openingHours = $openingHours;
        $this->idAnonym = $idAnonym;
        $this->institutionCode = $institutionCode;
        $this->department = $department;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getOpeningHours(): string
    {
        return $this->openingHours;
    }

    public function getIdAnonym(): string
    {
        return $this->idAnonym;
    }

    public function getInstitutionCode(): ?Institution
    {
        return $this->institutionCode;
    }

    public function getHomeDepartmentEmail(): string
    {
        return $this->homeDepartmentEmail;
    }

    public function setHomeDepartmentEmail(string $homeDepartmentEmail): self
    {
        $this->homeDepartmentEmail = $homeDepartmentEmail;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }
}
