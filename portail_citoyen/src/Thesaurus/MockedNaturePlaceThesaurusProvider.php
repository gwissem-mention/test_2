<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNaturePlaceThesaurusProvider implements NaturePlaceThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.nature.place.home' => 1,
            'pel.nature.place.park' => 2,
            'pel.nature.place.street' => 3,
            'pel.nature.place.market' => 4,
            'pel.nature.place.public.transport' => 5,
            'pel.nature.place.other' => 6,
            'pel.nature.place.unknown' => 7,
        ];
    }
}
