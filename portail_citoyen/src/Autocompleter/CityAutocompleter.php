<?php

declare(strict_types=1);

namespace App\Autocompleter;

use App\Referential\Entity\City;
use App\Referential\Repository\CityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'city'])]
class CityAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return City::class;
    }

    /**
     * @param CityRepository $repository
     */
    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        $qb = $repository->createQueryBuilder('city');

        if ('' === $query) {
            return $qb->andWhere('0 = 1');
        }

        return $qb
            ->andWhere(
                '
                        LOWER(city.label) LIKE LOWER(:filter)
                        OR LOWER(city.label) LIKE LOWER(:filterWithoutDash)
                        OR LOWER(city.label) LIKE LOWER(:filterWithDash)
                        OR city.postCode LIKE :filter
                    '
            )
            ->setParameter('filter', $query.'%')
            ->orderBy('city.label', 'ASC')
            ->setParameter('filterWithoutDash', str_replace('-', ' ', $query).'%')
            ->setParameter('filterWithDash', str_replace(' ', '-', $query).'%');
    }

    /**
     * @param City $entity
     */
    public function getLabel(object $entity): string
    {
        return $entity->getLabelAndPostCode();
    }

    /**
     * @param City $entity
     */
    public function getValue(object $entity): string
    {
        return $entity->getInseeCode();
    }

    public function isGranted(Security $security): bool
    {
        return true;
    }

    public function getGroupBy(): void
    {
    }
}
