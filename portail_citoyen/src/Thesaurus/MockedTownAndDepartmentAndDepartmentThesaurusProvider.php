<?php

declare(strict_types=1);

namespace App\Thesaurus;

use App\Enum\Department;

class MockedTownAndDepartmentAndDepartmentThesaurusProvider implements TownAndDepartmentThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.town.paris' => [
                'pel.department' => Department::SeventyFive->value,
                'value' => 1,
            ],
            'pel.town.marseille' => [
                'pel.department' => Department::Thirteen->value,
                'value' => 2,
            ],
            'pel.town.la.ciotat' => [
                'pel.department' => Department::Thirteen->value,
                'value' => 3,
            ],
            'pel.town.bordeaux' => [
                'pel.department' => Department::ThirtyThree->value,
                'value' => 4,
            ],
            'pel.town.merignac' => [
                'pel.department' => Department::ThirtyThree->value,
                'value' => 5,
            ],
            'pel.town.arcachon' => [
                'pel.department' => Department::ThirtyThree->value,
                'value' => 6,
            ],
        ];
    }
}
