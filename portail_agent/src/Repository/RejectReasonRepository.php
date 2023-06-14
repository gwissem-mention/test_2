<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RejectReason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RejectReason>
 *
 * @method RejectReason|null find($id, $lockMode = null, $lockVersion = null)
 * @method RejectReason|null findOneBy(array $criteria, array $orderBy = null)
 * @method RejectReason[]    findAll()
 * @method RejectReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RejectReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RejectReason::class);
    }

    public function save(RejectReason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RejectReason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
