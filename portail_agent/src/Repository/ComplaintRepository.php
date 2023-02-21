<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Complaint;
use App\Entity\User;
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
    public function findAsPaginator(array $order = [], int $start = 0, ?int $length = null, string $unit = null, ?User $agent = null): Paginator
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c, count(comments) as HIDDEN count_comments')
            ->addSelect('(CASE WHEN c.status = :status THEN 1 ELSE 0 END) AS HIDDEN assignmentPendingSort')
            ->andWhere('c.unitAssigned = :unit')
            ->orderBy('assignmentPendingSort', 'DESC')
            ->join('c.facts', 'facts')
            ->join('c.identity', 'identity')
            ->leftJoin('c.comments', 'comments')
            ->leftJoin('c.assignedTo', 'assignedTo')
            ->groupBy('c')
            ->addGroupBy('facts')
            ->addGroupBy('identity')
            ->addGroupBy('assignedTo.id')
            ->setParameter('unit', $unit)
            ->setParameter('status', Complaint::STATUS_ASSIGNMENT_PENDING);

        if ($agent instanceof User) {
            $qb->andWhere('assignedTo = :agent')
            ->setParameter('agent', $agent);
        }

        return new Paginator(DatatableFactory::createQuery($qb, $order, $start, $length));
    }
}
