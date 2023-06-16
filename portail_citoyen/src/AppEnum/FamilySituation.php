<?php

namespace App\AppEnum;

enum FamilySituation: int
{
    case Single = 1;
    case Cohabitation = 2;
    case Married = 3;
    case Divorced = 4;
    case CivilPartnership = 5;
    case Widowed = 6;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.family.situation.single' => self::Single->value,
            'pel.family.situation.cohabitation' => self::Cohabitation->value,
            'pel.family.situation.married' => self::Married->value,
            'pel.family.situation.divorced' => self::Divorced->value,
            'pel.family.situation.civil.partnership' => self::CivilPartnership->value,
            'pel.family.situation.widowed' => self::Widowed->value,
        ];
    }
}
