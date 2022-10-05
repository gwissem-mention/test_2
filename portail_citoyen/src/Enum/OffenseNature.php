<?php

namespace App\Enum;

enum OffenseNature: int
{
    case Robbery = 1;
    case Degradation = 2;
    case RobberyAndDegradation = 3;
    case Other = 4;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.complaint.offense.nature.robbery' => self::Robbery->value,
            'pel.complaint.offense.nature.degradation' => self::Degradation->value,
            'pel.complaint.offense.nature.robbery.and.degradation' => self::RobberyAndDegradation->value,
            'pel.complaint.offense.nature.other' => self::Other->value,
        ];
    }
}
