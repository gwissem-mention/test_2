<?php

declare(strict_types=1);

namespace App\AppEnum;

enum RegisteredVehicleNature: int
{
    case Truck = 1;
    case PersonalVehicle = 2;
    case Trailer = 3;
    case Caravan = 4;
    case Motorbike = 5;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.truck' => self::Truck->value,
            'pel.personal.vehicle' => self::PersonalVehicle->value,
            'pel.trailer' => self::Trailer->value,
            'pel.caravan' => self::Caravan->value,
            'pel.motorbike' => self::Motorbike->value,
        ];
    }

    public static function getLabel(int $nature = null): ?string
    {
        if (null === $nature) {
            return null;
        }

        $label = array_search($nature, self::getChoices(), true);

        if (false === $label) {
            return null;
        }

        return $label;
    }
}
