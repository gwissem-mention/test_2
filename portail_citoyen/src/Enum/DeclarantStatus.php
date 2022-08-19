<?php

namespace App\Enum;

enum DeclarantStatus: int
{
    case Victim = 1;
    case CorporationLegalRepresentative = 2;
    case PersonLegalRepresentative = 3;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'complaint.identity.victim' => self::Victim->value,
            'complaint.identity.corporation.legal.representative' => self::CorporationLegalRepresentative->value,
            'complaint.identity.person.legal.representative' => self::PersonLegalRepresentative->value,
        ];
    }
}
