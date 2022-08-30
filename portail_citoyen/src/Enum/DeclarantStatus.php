<?php

namespace App\Enum;

enum DeclarantStatus: int
{
    case Victim = 1;
    case PersonLegalRepresentative = 2;
    case CorporationLegalRepresentative = 3;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'complaint.identity.victim' => self::Victim->value,
            'complaint.identity.person.legal.representative' => self::PersonLegalRepresentative->value,
            'complaint.identity.corporation.legal.representative' => self::CorporationLegalRepresentative->value,
        ];
    }
}
