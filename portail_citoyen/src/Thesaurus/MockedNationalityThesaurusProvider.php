<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNationalityThesaurusProvider implements NationalityThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.nationality.france' => 1,
            'pel.nationality.spain' => 2,
            'pel.nationality.germany' => 3,
        ];
    }
}
