<?php

declare(strict_types=1);

namespace App\Thesaurus;

use App\Enum\Department;

class MockedDepartmentThesaurusProvider implements DepartmentThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'department.thirteen' => Department::Thirteen->value,
            'department.thirty.three' => Department::ThirtyThree->value,
            'department.seventy.five' => Department::SeventyFive->value,
        ];
    }
}
