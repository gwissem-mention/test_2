<?php

declare(strict_types=1);

namespace App\Twig;

use App\AppEnum\RegisteredVehicleNature;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RegisteredVehicleStatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('registered_vehicle_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(int $value = null): ?string
    {
        return RegisteredVehicleNature::getLabel($value);
    }
}
