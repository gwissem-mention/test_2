<?php

declare(strict_types=1);

namespace App\Autocompleter;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'user'])]
class UserAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * @param UserRepository $repository
     */
    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        $qb = $repository->createQueryBuilder('user');

        if (empty($query)) {
            return $qb->andWhere('0 = 1');
        }

        return $qb
            ->andWhere('LOWER(user.appellation) LIKE LOWER(:appellation)')
            ->orderBy('user.appellation', 'ASC')
            ->setParameter('appellation', '%'.$query.'%');
    }

    /**
     * @param User $entity
     */
    public function getLabel(object $entity): string
    {
        return (string) $entity->getAppellation();
    }

    /**
     * @param User $entity
     */
    public function getValue(object $entity): string
    {
        return strval($entity->getId());
    }

    public function isGranted(Security $security): bool
    {
        return $security->isGranted('IS_AUTHENTICATED');
    }
}