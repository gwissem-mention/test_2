<?php

declare(strict_types=1);

namespace App\Twig;

use App\Form\Model\Objects\ObjectModel;
use App\Thesaurus\ObjectCategoryThesaurusProviderInterface;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ObjectsExtension extends AbstractExtension
{
    public function __construct(private readonly ObjectCategoryThesaurusProviderInterface $objectCategoryThesaurusProvider)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('objects_quantity', [$this, 'getQuantity']),
            new TwigFilter('objects_amount', [$this, 'getAmount']),
            new TwigFilter('objects_has_unregistered_vehicle_stolen', [$this, 'hasUnregisteredVehicleStolen']),
        ];
    }

    /**
     * @param Collection<int, ObjectModel> $objects
     */
    public function getQuantity(Collection $objects): int
    {
        $quantity = 0;
        foreach ($objects as $object) {
            $quantity += $object->getQuantity() ?: 1;
        }

        return $quantity;
    }

    /**
     * @param Collection<int, ObjectModel> $objects
     */
    public function getAmount(Collection $objects): float
    {
        $amount = 0;
        foreach ($objects as $object) {
            $amount += $object->getAmount();
        }

        return $amount;
    }

    /**
     * @param Collection<int, ObjectModel> $objects
     */
    public function hasUnregisteredVehicleStolen(Collection $objects): bool
    {
        return !$objects->filter(fn (ObjectModel $object) => $this->objectCategoryThesaurusProvider->getChoices()['pel.object.category.unregistered.vehicle'] === $object->getCategory())->isEmpty();
    }
}
