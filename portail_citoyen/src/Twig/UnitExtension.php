<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UnitExtension extends AbstractExtension
{
    public function __construct(private readonly UnitRepository $unitRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('unit_name', [$this, 'getName']),
            new TwigFilter('unit', [$this, 'getUnit']),
        ];
    }

    public function getName(?string $serviceId): ?string
    {
        if (null === $serviceId) {
            return null;
        }

        return $this->unitRepository->findOneBy(['serviceId' => $serviceId])?->getName();
    }

    public function getUnit(?string $serviceId): ?Unit
    {
        if (null === $serviceId) {
            return null;
        }

        return $this->unitRepository->findOneBy(['serviceId' => $serviceId]);
    }
}
