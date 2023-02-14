<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Complaint;
use App\Factory\DatatableFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Complaint>
 *
 * @method Complaint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Complaint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Complaint[]    findAll()
 * @method Complaint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complaint::class);
    }

    public function save(Complaint $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Complaint $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array<int, array<string, string>> $order
     *
     * @return Paginator<Complaint>
     */
    public function findAsPaginator(array $order = [], int $start = 0, ?int $length = null): Paginator
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c, count(comments) as HIDDEN count_comments')
            ->join('c.facts', 'facts')
            ->join('c.identity', 'identity')
            ->leftJoin('c.comments', 'comments')
            ->leftJoin('c.assignedTo', 'assignedTo')
            ->groupBy('c')
            ->addGroupBy('facts')
            ->addGroupBy('identity')
            ->addGroupBy('assignedTo.id');

        return new Paginator(DatatableFactory::createQuery($qb, $order, $start, $length));
    }
}
