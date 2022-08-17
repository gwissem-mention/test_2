<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNationalityThesaurusProvider implements NationalityThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'nationality.france' => 1,
            'nationality.spain' => 2,
            'nationality.germany' => 3,
        ];
    }
}
