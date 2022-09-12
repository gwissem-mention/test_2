<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNaturePlaceThesaurusProvider implements NaturePlaceThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'nature.place.home' => 1,
            'nature.place.park' => 2,
            'nature.place.street' => 3,
            'nature.place.market' => 4,
            'nature.place.public.transport' => 5,
            'nature.place.other' => 6,
            'nature.place.unknown' => 7,
        ];
    }
}
