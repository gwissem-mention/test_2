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
    private const INSTITUTION_GN = '0';
    private const INSTITUTION_CODE_GN = 'GN';
    private const INSTITUTION_CODE_PN = 'PN';

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $cityCode;

    #[ORM\Column(length: 255)]
    private string $serviceCode;

    #[ORM\Column(length: 255)]
    private string $serviceCodeGN;

    #[ORM\Column(length: 255)]
    private string $institution;

    public function __construct(string $cityCode, string $serviceCode, string $serviceCodeGN, string $institution)
    {
        $this->cityCode = $cityCode;
        $this->serviceCode = $serviceCode;
        $this->institution = $institution;
        $this->serviceCodeGN = $serviceCodeGN;
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

    public function getInstitution(): string
    {
        return $this->institution;
    }

    public function getServiceCodeGN(): string
    {
        return $this->serviceCodeGN;
    }

    public function getInstitutionCode(): string
    {
        return self::INSTITUTION_GN === $this->institution ? self::INSTITUTION_CODE_GN : self::INSTITUTION_CODE_PN;
    }

    public function getServiceCodeForInstitution(): string
    {
        return self::INSTITUTION_GN === $this->institution ? $this->serviceCodeGN : $this->serviceCode;
    }
}
