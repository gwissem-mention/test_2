<?php

declare(strict_types=1);

namespace App\Autocompleter;

use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'unit'])]
class UnitAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return Unit::class;
    }

    /**
     * @param UnitRepository $repository
     */
    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        $qb = $repository->createQueryBuilder('unit');

        if (empty($query)) {
            return $qb->andWhere('0 = 1');
        }

        return $qb
            ->andWhere('LOWER(unit.name) LIKE LOWER(:name)')
            ->orderBy('unit.name', 'ASC')
            ->setParameter('name', '%'.$query.'%')
        ;
    }

    /**
     * @param Unit $entity
     */
    public function getLabel(object $entity): string
    {
        return $entity->getName();
    }

    /**
     * @param Unit $entity
     */
    public function getValue(object $entity): string
    {
        return $entity->getCode();
    }

    public function isGranted(Security $security): bool
    {
        return $security->isGranted('IS_AUTHENTICATED');
    }
}