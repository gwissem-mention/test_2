<?php

namespace App\Enum;

enum OffenseNature: int
{
    case Robbery = 1;
    case Degradation = 2;
    case Other = 3;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'complaint.offense.nature.robbery' => self::Robbery->value,
            'complaint.offense.nature.degradation' => self::Degradation->value,
            'complaint.offense.nature.other' => self::Other->value,
        ];
    }
}
