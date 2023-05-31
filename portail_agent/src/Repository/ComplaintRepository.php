<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Complaint;
use App\Entity\User;
use App\Factory\DatatableFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
    public function findAsPaginator(array $order = [], int $start = 0, ?int $length = null, string $unit = null, ?User $agent = null, ?string $searchQuery = null): Paginator
    {
        $status = match ($searchQuery) {
            'a-attribuer' => Complaint::STATUS_ASSIGNMENT_PENDING,
            'attente-rdv' => Complaint::STATUS_APPOINTMENT_PENDING,
            'attente-reorientation' => Complaint::STATUS_UNIT_REDIRECTION_PENDING,
            'attente-reattribution' => Complaint::STATUS_REASSIGNMENT_PENDING,
            'cloturee' => Complaint::STATUS_CLOSED,
            'en-cours-lrp' => Complaint::STATUS_ONGOING_LRP,
            default => null,
        };

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

        $order = $this->addOrderByPriority($order);

        if ($agent instanceof User) {
            $qb->andWhere('assignedTo = :agent')
                ->setParameter('agent', $agent);
        }

        if (!is_null($status)) {
            $qb->andWhere('c.status = :status')
                ->setParameter('status', $status)
            ;
        } elseif (!empty($searchQuery)) {
            $keywords = explode(' ', $searchQuery);
            $andX = $qb->expr()->andX();
            $i = 0;
            foreach ($keywords as $keyword) {
                $parameter = 'keyword_'.$i;
                $orX = $qb->expr()->orX(
                    $qb->expr()->like('c.declarationNumber', $qb->expr()->literal("%$keyword%")),
                    $qb->expr()->like('LOWER(unaccent(identity.firstname))', 'LOWER(unaccent(:'.$parameter.'))'),
                    $qb->expr()->like('LOWER(unaccent(identity.lastname))', 'LOWER(unaccent(:'.$parameter.'))'),
                    $qb->expr()->like('LOWER(unaccent(assignedTo.appellation))', 'LOWER(unaccent(:'.$parameter.'))'),
                );
                $andX->add($orX);
                $qb->setParameter($parameter, '%'.$keyword.'%');
                ++$i;
            }
            $qb->andWhere($andX);
        }

        return new Paginator(DatatableFactory::createQuery($qb, $order, $start, $length));
    }

    public function createNotifiableComplaintsQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.processingDeadline <= :now')
            ->andWhere('c.deadlineNotified = :bool')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('bool', false)
        ;
    }

    /**
     * @return Complaint[]
     */
    public function getNotifiableComplaintsForProcessingDeadline(): array
    {
        /** @var Complaint[] $result */
        $result = $this->createNotifiableComplaintsQueryBuilder()
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function countNotifiableComplaintsForProcessingDeadline(): int
    {
        /** @var int $result */
        $result = $this->createNotifiableComplaintsQueryBuilder()
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

    /**
     * @param array<int, array<string, string>> $order
     *
     * @return array<int, array<string, string>>
     */
    private function addOrderByPriority(array $order): array
    {
        if ('status' != $order[0]['field']) {
            $order[] = [
                'field' => 'status',
                'dir' => 'asc',
            ];
        }

        $order[] = [
            'field' => 'priority',
            'dir' => 'asc',
        ];

        return $order;
    }
}
