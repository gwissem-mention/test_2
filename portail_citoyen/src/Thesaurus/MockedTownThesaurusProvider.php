<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedTownThesaurusProvider implements TownAndDepartmentThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.town.paris' => 1,
            'pel.town.marseille' => 2,
            'pel.town.la.ciotat' => 3,
            'pel.town.bordeaux' => 4,
            'pel.town.merignac' => 5,
            'pel.town.arcachon' => 6,
        ];
    }
}
