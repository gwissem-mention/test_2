<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\CityUnitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityUnitRepository::class)]
#[ORM\Index(fields: ['cityCode'], name: 'city_unit_city_code_idx')]
#[ORM\Index(fields: ['unitCode'], name: 'city_unit_unit_code_idx')]
class CityUnit
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $cityCode;

    #[ORM\Column(length: 255)]
    private string $unitCode;

    public function __construct(string $cityCode, string $unitCode)
    {
        $this->cityCode = $cityCode;
        $this->unitCode = $unitCode;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    public function getUnitCode(): string
    {
        return $this->unitCode;
    }
}
