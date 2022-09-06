<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNaturePlacePublicTransportThesaurusProvider implements NaturePlacePublicTransportThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'nature.place.public.transport.plane' => 1,
            'nature.place.public.transport.subway' => 2,
            'nature.place.public.transport.bus' => 3,
            'nature.place.public.transport.train.station' => 4,
            'nature.place.public.transport.tram' => 5,
            'nature.place.public.transport.boat' => 6,
            'nature.place.public.transport.autocar' => 7,
        ];
    }
}
