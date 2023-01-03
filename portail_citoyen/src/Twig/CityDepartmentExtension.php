<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Repository\CityRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CityDepartmentExtension extends AbstractExtension
{
    public function __construct(private readonly CityRepository $cityRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('city_get_department', [$this, 'getDepartment']),
        ];
    }

    public function getDepartment(string $inseeCode): ?string
    {
        $city = $this->cityRepository->findOneBy(['inseeCode' => $inseeCode]);

        return $city?->getDepartment();
    }
}
