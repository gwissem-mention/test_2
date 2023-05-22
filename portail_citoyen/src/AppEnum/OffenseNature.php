<?php

namespace App\AppEnum;

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
            'pel.complaint.offense.nature.robbery' => self::Robbery->value,
            'pel.complaint.offense.nature.degradation' => self::Degradation->value,
            'pel.complaint.offense.nature.other' => self::Other->value,
        ];
    }
}
