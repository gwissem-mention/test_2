<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\CityServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityServiceRepository::class)]
#[ORM\Index(fields: ['cityCode'], name: 'city_code_idx')]
#[ORM\Index(fields: ['serviceCode'], name: 'service_code_idx')]
class CityService
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $cityCode;

    #[ORM\Column(length: 255)]
    private string $serviceCode;

    public function __construct(string $cityCode, string $serviceCode)
    {
        $this->cityCode = $cityCode;
        $this->serviceCode = $serviceCode;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    public function getServiceCode(): string
    {
        return $this->serviceCode;
    }
}
