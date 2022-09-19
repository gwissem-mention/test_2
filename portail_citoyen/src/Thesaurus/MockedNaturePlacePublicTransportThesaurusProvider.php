<?php

declare(strict_types=1);

namespace App\Thesaurus;

class MockedNaturePlacePublicTransportThesaurusProvider implements NaturePlacePublicTransportThesaurusProviderInterface
{
    public function getChoices(): array
    {
        return [
            'pel.nature.place.public.transport.plane' => 1,
            'pel.nature.place.public.transport.subway' => 2,
            'pel.nature.place.public.transport.bus' => 3,
            'pel.nature.place.public.transport.train.station' => 4,
            'pel.nature.place.public.transport.tram' => 5,
            'pel.nature.place.public.transport.boat' => 6,
            'pel.nature.place.public.transport.autocar' => 7,
        ];
    }
}
