<?php

declare(strict_types=1);

namespace App\Twig;

use App\Form\Model\Objects\ObjectModel;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ObjectsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('objects_quantity', [$this, 'getQuantity']),
            new TwigFilter('objects_amount', [$this, 'getAmount']),
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
    public function getAmount(Collection $objects): int
    {
        $amount = 0;
        foreach ($objects as $object) {
            $amount += $object->getAmount();
        }

        return $amount;
    }
}
