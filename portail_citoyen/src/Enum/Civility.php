<?php

namespace App\Enum;

enum Civility: int
{
    case M = 1;
    case Mme = 2;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.m' => self::M->value,
            'pel.mme' => self::Mme->value,
        ];
    }
}
