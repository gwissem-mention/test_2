<?php

declare(strict_types=1);

namespace App\Autocompleter;

use App\Referential\Entity\Job;
use App\Referential\Repository\JobRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'job'])]
class JobAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return Job::class;
    }

    /**
     * @param JobRepository $repository
     */
    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        $qb = $repository->createQueryBuilder('job');

        return $qb
            ->andWhere('LOWER(job.label) LIKE LOWER(:filter)')
            ->setParameter('filter', '%'.$query.'%')
            ->orderBy('job.label', 'ASC')
            ->setMaxResults(5);
    }

    /**
     * @param Job $entity
     */
    public function getLabel(object $entity): string
    {
        return $entity->getLabel();
    }

    /**
     * @param Job $entity
     */
    public function getValue(object $entity): string
    {
        return $entity->getCode();
    }

    public function isGranted(Security $security): bool
    {
        return true;
    }
}
