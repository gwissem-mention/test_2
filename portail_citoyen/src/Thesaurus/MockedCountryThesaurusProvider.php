<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedCountryThesaurusProvider implements CountryThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'country.france' => 1,
            'country.spain' => 2,
            'country.germany' => 3,
        ];
    }
}
