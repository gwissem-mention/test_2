<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Entity\RegisteredVehicleNature;
use App\Referential\Repository\RegisteredVehicleNatureRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RegisteredVehicleStatusExtension extends AbstractExtension
{
    public function __construct(private readonly RegisteredVehicleNatureRepository $registeredVehicleNatureRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('registered_vehicle_label', [$this, 'getLabel']),
        ];
    }

    public function getLabel(?int $registeredVehicleNatureId): string
    {
        if (null === $registeredVehicleNatureId) {
            return '';
        }

        $registeredVehicleNature = $this->registeredVehicleNatureRepository->find($registeredVehicleNatureId);

        return $registeredVehicleNature instanceof RegisteredVehicleNature ? ucfirst($registeredVehicleNature->getLabel()) : '';
    }
}
