<?php

declare(strict_types=1);

namespace App\Twig;

use App\Referential\Entity\NaturePlace;
use App\Referential\Repository\NaturePlaceRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NaturePlaceExtension extends AbstractExtension
{
    private const NATURES_PLACES_MAP_DISPLAYED = [
        'Voie publique',
        'Parking',
        'Établissement scolaire',
        'Lieu de culte ou de recueillement',
        'Lieu de loisirs',
    ];

    public function __construct(private readonly NaturePlaceRepository $naturePlaceRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('nature_place_map_displayed', [$this, 'isMapDisplayed']),
            new TwigFilter('nature_place_label', [$this, 'getLabel']),
        ];
    }

    public function isMapDisplayed(?int $naturePlaceId): bool
    {
        if (null === $naturePlaceId) {
            return false;
        }

        $naturePlace = $this->naturePlaceRepository->find($naturePlaceId);

        return $naturePlace instanceof NaturePlace && in_array($naturePlace->getLabel(), self::NATURES_PLACES_MAP_DISPLAYED, true);
    }

    public function getLabel(?int $naturePlaceId): string
    {
        if (null === $naturePlaceId) {
            return '';
        }

        $naturePlace = $this->naturePlaceRepository->find($naturePlaceId);

        return $naturePlace instanceof NaturePlace ? $naturePlace->getLabel() : '';
    }
}
