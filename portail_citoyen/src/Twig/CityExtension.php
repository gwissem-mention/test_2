<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Entity\City;
use App\Referential\Repository\CityRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CityExtension extends AbstractExtension
{
    public function __construct(private readonly CityRepository $cityRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('city_department', [$this, 'getDepartment']),
            new TwigFilter('city', [$this, 'getCity']),
        ];
    }

    public function getDepartment(string $inseeCode): ?string
    {
        return $this->cityRepository->findOneBy(['inseeCode' => $inseeCode])?->getDepartment();
    }

    public function getCity(string $inseeCode): ?City
    {
        return $this->cityRepository->findOneBy(['inseeCode' => $inseeCode]);
    }
}
