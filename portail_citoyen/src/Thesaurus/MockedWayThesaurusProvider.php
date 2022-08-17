<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedWayThesaurusProvider implements WayThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'way.one' => 1,
            'way.two' => 2,
            'way.three' => 3,
        ];
    }
}
