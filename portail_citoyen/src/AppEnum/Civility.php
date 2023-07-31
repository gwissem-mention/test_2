<?php

declare(strict_types=1);

namespace App\AppEnum;

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
