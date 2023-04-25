<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Enum\Institution;
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

    public function __construct(string $serviceId, string $code, string $name, string $latitude, string $longitude, string $address, string $phone, string $openingHours, string $idAnonym, ?Institution $institutionCode)
    {
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
}
