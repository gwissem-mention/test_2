<?php

declare(strict_types=1);

namespace App\Autocompleter;

use App\Referential\Entity\Agent;
use App\Referential\Repository\AgentRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'agent'])]
class AgentAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return Agent::class;
    }

    /**
     * @param AgentRepository $repository
     */
    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        $qb = $repository->createQueryBuilder('agent');

        if ('' === $query) {
            return $qb->andWhere('0 = 1');
        }

        return $qb
            ->andWhere(
                '
                        LOWER(agent.firstName) LIKE LOWER(:first_name)
                        OR LOWER(agent.lastName) LIKE LOWER(:last_name)
                    '
            )
            ->orderBy('agent.firstName', 'ASC')
            ->addOrderBy('agent.lastName', 'ASC')
            ->setParameter('first_name', $query.'%')
            ->setParameter('last_name', $query.'%');
    }

    /**
     * @param Agent $entity
     */
    public function getLabel(object $entity): string
    {
        return $entity->getName();
    }

    /**
     * @param Agent $entity
     */
    public function getValue(object $entity): string
    {
        return strval($entity->getId());
    }

    public function isGranted(Security $security): bool
    {
        return true;
    }
}
