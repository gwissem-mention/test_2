<?php

declare(strict_types=1);

namespace App\Tests\Behat\MockedClasses;

use App\Etalab\EtalabApiClientInterface;

class EtalabApiClient implements EtalabApiClientInterface
{
    /**
     * @return array<string, array<int, array<string, mixed>>|string|int>
     */
    public function search(string $query, int $limit = 5): array
    {
        return [
            'type' => 'FeatureCollection',
            'version' => 'draft',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            2.376252,
                            48.864924,
                        ],
                    ],
                    'properties' => [
                        'label' => 'Avenue de la République 75011 Paris',
                        'score' => 0.9801072727272726,
                        'id' => '75111_8158',
                        'name' => 'Avenue de la République',
                        'postcode' => '75011',
                        'citycode' => '75111',
                        'x' => 654241.06,
                        'y' => 6862946.61,
                        'city' => 'Paris',
                        'district' => 'Paris 11e Arrondissement',
                        'context' => '75, Paris, Île-de-France',
                        'type' => 'street',
                        'importance' => 0.78118,
                        'street' => 'Avenue de la République',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            5.696979,
                            45.179384,
                        ],
                    ],
                    'properties' => [
                        'label' => 'Avenue de la République 38170 Seyssinet-Pariset',
                        'score' => 0.7032316883116884,
                        'id' => '38485_0570',
                        'name' => 'Avenue de la République',
                        'postcode' => '38170',
                        'citycode' => '38485',
                        'x' => 911798.61,
                        'y' => 6456959.14,
                        'city' => 'Seyssinet-Pariset',
                        'context' => '38, Isère, Auvergne-Rhône-Alpes',
                        'type' => 'street',
                        'importance' => 0.66412,
                        'street' => 'Avenue de la République',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            2.365475,
                            48.867039,
                        ],
                    ],
                    'properties' => [
                        'label' => 'Place de la République 75011 Paris',
                        'score' => 0.6459463636363636,
                        'id' => '75111_8159',
                        'name' => 'Place de la République',
                        'postcode' => '75011',
                        'citycode' => '75111',
                        'x' => 653452.35,
                        'y' => 6863188.08,
                        'city' => 'Paris',
                        'district' => 'Paris 11e Arrondissement',
                        'context' => '75, Paris, Île-de-France',
                        'type' => 'street',
                        'importance' => 0.63666,
                        'street' => 'Place de la République',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            2.363976,
                            48.866683,
                        ],
                    ],
                    'properties' => [
                        'label' => 'Place de la République 75003 Paris',
                        'score' => 0.6425018181818181,
                        'id' => '75103_8159',
                        'name' => 'Place de la République',
                        'postcode' => '75003',
                        'citycode' => '75103',
                        'x' => 653342.07,
                        'y' => 6863149.38,
                        'city' => 'Paris',
                        'district' => 'Paris 3e Arrondissement',
                        'context' => '75, Paris, Île-de-France',
                        'type' => 'street',
                        'importance' => 0.59877,
                        'street' => 'Place de la République',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            2.363073,
                            48.868221,
                        ],
                    ],
                    'properties' => [
                        'label' => 'Place de la République 75010 Paris',
                        'score' => 0.6420609090909091,
                        'id' => '75110_8159',
                        'name' => 'Place de la République',
                        'postcode' => '75010',
                        'citycode' => '75110',
                        'x' => 653277.21,
                        'y' => 6863320.93,
                        'city' => 'Paris',
                        'district' => 'Paris 10e Arrondissement',
                        'context' => '75, Paris, Île-de-France',
                        'type' => 'street',
                        'importance' => 0.59392,
                        'street' => 'Place de la République',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            -0.607421,
                            44.839502,
                        ],
                    ],
                    'properties' => [
                        'label' => 'Avenue de la République 33000 Bordeaux',
                        'score' => 0.9855072727272726,
                        'id' => '33063_8132',
                        'name' => 'Avenue de la République',
                        'postcode' => '33000',
                        'citycode' => '33063',
                        'x' => 415022.25,
                        'y' => 6422103.65,
                        'city' => 'Bordeaux',
                        'context' => '33, Gironde, Nouvelle-Aquitaine',
                        'type' => 'street',
                        'importance' => 0.84058,
                        'street' => 'Avenue de la République',
                    ],
                ],
            ],
            'attribution' => 'BAN',
            'licence' => 'ETALAB-2.0',
            'query' => 'avenue de la république bordeaux',
            'limit' => 5,
        ];
    }
}
