<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedWayThesaurusProvider implements WayThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.way.one' => 1,
            'pel.way.two' => 2,
            'pel.way.three' => 3,
        ];
    }
}
