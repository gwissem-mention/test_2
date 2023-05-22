<?php

namespace App\AppEnum;

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
            'pel.complaint.identity.victim' => self::Victim->value,
            'pel.complaint.identity.person.legal.representative' => self::PersonLegalRepresentative->value,
            'pel.complaint.identity.corporation.legal.representative' => self::CorporationLegalRepresentative->value,
        ];
    }
}
