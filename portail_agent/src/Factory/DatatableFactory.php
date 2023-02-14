<?php

declare(strict_types=1);

namespace App\Factory;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class DatatableFactory
{
    /**
     * @param array<int, array<string, string>> $order
     */
    public static function createQuery(QueryBuilder $qb, array $order, int $start, ?int $length = null): Query
    {
        foreach ($order as $item) {
            $column = $item['field'];
            $orderDir = $item['dir'];

            // Field from main entity
            if (!str_contains($column, '__')) {
                $column = $qb->getRootAliases()[0].'.'.$column;
            } // Field from joined entity
            elseif (!str_contains($column, '___')) {
                $column = str_replace(['__', '_'], ['', '.'], $column);
            } // Custom field
            else {
                $column = str_replace('___', '', $column);
            }

            $qb->addOrderBy($column, $orderDir);
        }

        if (!is_null($length)) {
            $qb->setMaxResults($length);
        }

        return $qb->getQuery()->setFirstResult($start);
    }
}
