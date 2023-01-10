<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\FactsObject;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ObjectsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('objects_quantity', [$this, 'getQuantity']),
            new TwigFilter('objects_total_amount', [$this, 'getTotalAmount']),
        ];
    }

    /**
     * @param Collection<int, FactsObject> $objects
     */
    public function getQuantity(Collection $objects): int
    {
        return $objects->count();
    }

    /**
     * @param Collection<int, FactsObject> $objects
     */
    public function getTotalAmount(Collection $objects): float
    {
        $amount = 0;
        foreach ($objects as $object) {
            $amount += $object->getAmount();
        }

        return $amount;
    }
}
