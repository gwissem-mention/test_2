<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNaturePlaceOtherThesaurusProvider implements NaturePlaceOtherThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'nature.place.other.camping' => 1,
            'nature.place.other.restaurant' => 2,
            'nature.place.other.park' => 3,
            'nature.place.other.market' => 4,
        ];
    }
}
