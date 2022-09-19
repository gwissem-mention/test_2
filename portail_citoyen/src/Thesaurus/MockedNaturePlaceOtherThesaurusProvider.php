<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNaturePlaceOtherThesaurusProvider implements NaturePlaceOtherThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.nature.place.other.camping' => 1,
            'pel.nature.place.other.restaurant' => 2,
            'pel.nature.place.other.park' => 3,
            'pel.nature.place.other.market' => 4,
        ];
    }
}
