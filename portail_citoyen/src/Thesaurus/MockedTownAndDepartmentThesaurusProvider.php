<?php

declare(strict_types=1);

namespace App\Thesaurus;

use App\Enum\Department;

class MockedTownAndDepartmentThesaurusProvider implements TownAndDepartmentThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.town.paris' => [
                'pel.department' => Department::SeventyFive->value,
                'value' => 1,
                'code_insee' => '75107',
            ],
            'pel.town.marseille' => [
                'pel.department' => Department::Thirteen->value,
                'value' => 2,
                'code_insee' => '13055',
            ],
            'pel.town.la.ciotat' => [
                'pel.department' => Department::Thirteen->value,
                'value' => 3,
                'code_insee' => '13028',
            ],
            'pel.town.bordeaux' => [
                'pel.department' => Department::ThirtyThree->value,
                'value' => 4,
                'code_insee' => '33063',
            ],
            'pel.town.merignac' => [
                'pel.department' => Department::ThirtyThree->value,
                'value' => 5,
                'code_insee' => '33281',
            ],
            'pel.town.arcachon' => [
                'pel.department' => Department::ThirtyThree->value,
                'value' => 6,
                'code_insee' => '33009',
            ],
        ];
    }
}
