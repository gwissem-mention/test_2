<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\RegisteredVehicleNatureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegisteredVehicleNatureRepository::class)]
#[ORM\Index(fields: ['label'], name: 'registered_vehicle_nature_label_idx')]
#[ORM\Index(fields: ['code'], name: 'registered_vehicle_nature_code_idx')]
class RegisteredVehicleNature
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $label;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $code;

    public function __construct(string $label, string $code)
    {
        $this->code = $code;
        $this->label = $label;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
}
