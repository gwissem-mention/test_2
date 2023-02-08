<?php

declare(strict_types=1);

namespace App\Twig;

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
        ];
    }

    public function getName(?string $code): ?string
    {
        if (is_null($code)) {
            return null;
        }

        return $this->unitRepository->findOneBy(['code' => $code])?->getName();
    }
}