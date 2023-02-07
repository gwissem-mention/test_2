<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ComplaintCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComplaintCount>
 *
 * @method ComplaintCount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintCount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintCount[]    findAll()
 * @method ComplaintCount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintCountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplaintCount::class);
    }

    public function save(ComplaintCount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ComplaintCount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
