<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UploadReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UploadReport>
 *
 * @method UploadReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadReport[]    findAll()
 * @method UploadReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UploadReport::class);
    }

    public function save(UploadReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UploadReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
