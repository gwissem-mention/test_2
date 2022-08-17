<?php

declare(strict_types=1);

namespace App\Thesaurus;

use App\Enum\Department;

class MockedTownAndDepartmentAndDepartmentThesaurusProvider implements TownAndDepartmentThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'town.paris' => [
                'department' => Department::SeventyFive->value,
                'value' => 1,
            ],
            'town.marseille' => [
                'department' => Department::Thirteen->value,
                'value' => 2,
            ],
            'town.la.ciotat' => [
                'department' => Department::Thirteen->value,
                'value' => 3,
            ],
            'town.bordeaux' => [
                'department' => Department::ThirtyThree->value,
                'value' => 4,
            ],
            'town.merignac' => [
                'department' => Department::ThirtyThree->value,
                'value' => 5,
            ],
            'town.arcachon' => [
                'department' => Department::ThirtyThree->value,
                'value' => 6,
            ],
        ];
    }
}
