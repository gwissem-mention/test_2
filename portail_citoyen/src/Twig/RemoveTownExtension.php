<?php

declare(strict_types=1);

namespace App\Twig;

use App\Form\DataTransformer\TownToTownAndDepartmentTransformer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RemoveTownExtension extends AbstractExtension
{
    public function __construct(private readonly TownToTownAndDepartmentTransformer $townToTownAndDepartmentTransformer)
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('removeTown', [$this, 'removeTown']),
        ];
    }

    public function removeTown(string $town): string
    {
        return $this->townToTownAndDepartmentTransformer->reverseTransform($town);
    }
}
