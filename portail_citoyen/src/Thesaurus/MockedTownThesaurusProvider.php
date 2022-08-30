<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedTownThesaurusProvider implements TownAndDepartmentThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'town.paris' => 1,
            'town.marseille' => 2,
            'town.la.ciotat' => 3,
            'town.bordeaux' => 4,
            'town.merignac' => 5,
            'town.arcachon' => 6,
        ];
    }
}
