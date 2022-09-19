<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedJobThesaurusProvider implements JobThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.job.policeman' => 1,
            'pel.job.constable' => 2,
            'pel.job.none' => 3,
        ];
    }
}
