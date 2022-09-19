<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedCountryThesaurusProvider implements CountryThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.country.france' => 1,
            'pel.country.spain' => 2,
            'pel.country.germany' => 3,
        ];
    }
}
