<?php

declare(strict_types=1);

namespace App\Etalab;

class AddressZoneChecker
{
    public function isInsideGironde(float $latitude, float $longitude): bool
    {
        $girondeBounds = [
            'north' => 45.035,
            'south' => 44.587,
            'east' => -0.442,
            'west' => -0.809,
        ];

        return
            $latitude >= $girondeBounds['south']
            && $latitude <= $girondeBounds['north']
            && $longitude >= $girondeBounds['west']
            && $longitude <= $girondeBounds['east']
        ;
    }
}
