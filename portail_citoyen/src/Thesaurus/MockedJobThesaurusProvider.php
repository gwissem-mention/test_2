<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedJobThesaurusProvider implements JobThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'job.policeman' => 1,
            'job.constable' => 2,
            'job.none' => 3,
        ];
    }
}
