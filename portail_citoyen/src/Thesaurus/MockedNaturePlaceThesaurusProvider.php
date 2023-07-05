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
            'pel.nature.place.educational.establishment' => 3,
            'pel.nature.place.internet' => 4,
            'pel.nature.place.street' => 5,
            'pel.nature.place.public.transport' => 6,
            'pel.nature.place.worship' => 7,
            'pel.nature.place.business' => 8,
            'pel.nature.place.market' => 9,
            'pel.nature.place.leisure.place' => 10,
            'pel.nature.place.other' => 11,
        ];
    }
}
