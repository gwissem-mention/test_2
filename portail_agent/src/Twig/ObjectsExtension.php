<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\FactsObjects\AbstractObject;
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
            new TwigFilter('objects_alerts', [$this, 'getAlerts']),
            new TwigFilter('object_type', [$this, 'getType']),
        ];
    }

    /**
     * @param Collection<int, AbstractObject> $objects
     */
    public function getQuantity(Collection $objects): int
    {
        return $objects->count();
    }

    /**
     * @param Collection<int, AbstractObject> $objects
     */
    public function getTotalAmount(Collection $objects): float
    {
        $amount = 0;
        foreach ($objects as $object) {
            $amount += $object->getAmount();
        }

        return $amount;
    }

    /**
     * @param Collection<int, AbstractObject> $objects
     */
    public function getAlerts(Collection $objects): int
    {
        $alerts = 0;
        foreach ($objects as $object) {
            $alerts += $object->getAlertNumber();
        }

        return $alerts;
    }

    public function getType(AbstractObject $object): string
    {
        return get_class($object);
    }
}
