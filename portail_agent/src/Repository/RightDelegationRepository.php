<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RightDelegation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RightDelegation>
 *
 * @method RightDelegation|null find($id, $lockMode = null, $lockVersion = null)
 * @method RightDelegation|null findOneBy(array $criteria, array $orderBy = null)
 * @method RightDelegation[]    findAll()
 * @method RightDelegation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method RightDelegation|null findOneByDelegatingAgent($delegatingAgent)
 */
class RightDelegationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RightDelegation::class);
    }

    public function save(RightDelegation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCurrentRightDelegationByDelegatingAgent(?User $delegatingAgent): ?RightDelegation
    {
        $today = (new \DateTimeImmutable())->setTime(0, 0);

        /** @var RightDelegation|null $result */
        $result = $this->createQueryBuilder('rd')
            ->where('rd.delegatingAgent = :delegatingAgent')
            ->andWhere('rd.endDate >= :today')
            ->setParameter('delegatingAgent', $delegatingAgent)
            ->setParameter('today', $today)
            ->orderBy('rd.startDate', 'ASC')
            ->orderBy('rd.endDate', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result;
    }
}
