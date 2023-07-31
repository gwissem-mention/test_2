<?php

declare(strict_types=1);

namespace App\AppEnum;

enum DeclarantStatus: int
{
    case Victim = 1;
    /* Person Legal Representative must be hidden for the experimentation */
    // case PersonLegalRepresentative = 2;
    case CorporationLegalRepresentative = 3;

    /**
     * @return array<string, int>
     */
    public static function getChoices(): array
    {
        return [
            'pel.complaint.identity.victim' => self::Victim->value,
            /* Person Legal Representative must be hidden for the experimentation */
            // 'pel.complaint.identity.person.legal.representative' => self::PersonLegalRepresentative->value,
            'pel.complaint.identity.corporation.legal.representative' => self::CorporationLegalRepresentative->value,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function getChoicesHelp(): array
    {
        return [
            self::Victim->value => 'pel.complaint.identity.victim.hint',
            /* Person Legal Representative must be hidden for the experimentation */
//            self::PersonLegalRepresentative->value => 'pel.complaint.identity.person.legal.representative.hint',
            self::CorporationLegalRepresentative->value => 'pel.complaint.identity.corporation.legal.representative.hint',
        ];
    }

    /**
     * @return array<int, mixed>
     */
    public static function getChoicesImg(): array
    {
        return [
            self::Victim->value => [
                'src' => 'build/images/avatar.svg',
            ],
            /* Person Legal Representative must be hidden for the experimentation */
            // self::PersonLegalRepresentative->value => [
            //     'src' => 'build/images/document-download-2.svg',
            // ],
            self::CorporationLegalRepresentative->value => [
                'src' => 'build/images/contract.svg',
            ],
        ];
    }
}
