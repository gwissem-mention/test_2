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
    public const SEARCH_ALERT = 'alert';
    public const SEARCH_APPOINTMENT_PENDING = 'appointment-pending';
    public const SEARCH_APPOINTMENT_PLANNED = 'appointment-planned';
    public const SEARCH_ASSIGNED = 'assigned';
    public const SEARCH_ASSIGNMENT_PENDING = 'assignment-pending';
    public const SEARCH_CLOSED = 'closed';
    public const SEARCH_CLOSURE_PENDING = 'closure-pending';
    public const SEARCH_EXCEEDS_DEADLINE = 'exceeds-deadline';
    public const SEARCH_REJECTED = 'rejected';
    public const SEARCH_ONGOING_LRP = 'ongoing-lrp';
    public const SEARCH_REACHES_DEADLINE = 'reaches-deadline';
    public const SEARCH_UNIT_REDIRECTION_PENDING = 'unit-redirection-pending';

    public function __construct(
        ManagerRegistry $registry,
        private readonly string $oodriveDeclarationCleanUpPeriod,
        private readonly string $oodriveAttachmentsOnlyCleanUpPeriod,
        private readonly string $oodriveReportCleanUpPeriod,
    ) {
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
    public function findAsPaginator(array $order = [], int $start = 0, int $length = null, string $unit = null, User $agent = null, string $searchQuery = null): Paginator
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

        $order = $this->addOrderByPriority($order);

        if ($agent instanceof User) {
            $qb->andWhere('assignedTo = :agent')
                ->setParameter('agent', $agent);
        }

        $qb = $this->search($qb, $searchQuery);

        return new Paginator(DatatableFactory::createQuery($qb, $order, $start, $length));
    }

    public function createNotifiableComplaintsQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.processingDeadline <= :now')
            ->andWhere('c.deadlineNotified = :bool')
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('bool', false);
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
     * @return array<Complaint>
     */
    public function findComplaintsForOodriveDeclarationCleanUp(): array
    {
        $date = new \DateTimeImmutable("-$this->oodriveDeclarationCleanUpPeriod");
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.createdAt < :date')
            ->andWhere('c.oodriveCleanedUpDeclarationAt IS NULL')
            ->setParameter('date', $date);

        /** @var array<Complaint> $complaints */
        $complaints = $qb->getQuery()->getResult();

        return $complaints;
    }

    /**
     * @return array<Complaint>
     */
    public function findComplaintsForOodriveAttachmentsOnlyCleanUp(): array
    {
        $date = new \DateTimeImmutable("-$this->oodriveAttachmentsOnlyCleanUpPeriod");
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.rejectedAt < :date OR c.closedAt < :date')
            ->andWhere('c.oodriveCleanedUpAttachmentsAt IS NULL')
            ->setParameter('date', $date);

        /** @var array<Complaint> $complaints */
        $complaints = $qb->getQuery()->getResult();

        return $complaints;
    }

    /**
     * @return array<Complaint>
     */
    public function findComplaintsForOodriveReportCleanUp(): array
    {
        $date = new \DateTimeImmutable("-$this->oodriveReportCleanUpPeriod");
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.closedAt < :date')
            ->andWhere('c.oodriveCleanedUpReportAt IS NULL')
            ->setParameter('date', $date);

        /** @var array<Complaint> $complaints */
        $complaints = $qb->getQuery()->getResult();

        return $complaints;
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

    /**
     * @return array<Complaint>
     */
    public function getComplaintsWithAppointmentADayAgo(): array
    {
        /** @var Complaint[] $result */
        $result = $this->createComplaintsWithAppointmentADayAgoQueryBuilder()
            ->getQuery()
            ->getResult();

        return $result;
    }

    private function createComplaintsWithAppointmentADayAgoQueryBuilder(): QueryBuilder
    {
        $now = new \DateTimeImmutable('-1 day');

        return $this->createQueryBuilder('c')
            ->andWhere('c.appointmentTime <= :now')
            ->andWhere('c.appointmentNotificationSentAt is null')
            ->andWhere('c.assignedTo is not null')
            ->andWhere('c.status <> :PvStatus')
            ->setParameter('now', $now)
            ->setParameter('PvStatus', Complaint::STATUS_CLOSED);
    }

    private function search(QueryBuilder $qb, ?string $searchQuery): QueryBuilder
    {
        $status = match ($searchQuery) {
            'a-attribuer' => Complaint::STATUS_ASSIGNMENT_PENDING,
            'attente-rdv' => Complaint::STATUS_APPOINTMENT_PENDING,
            'attente-reorientation' => Complaint::STATUS_UNIT_REDIRECTION_PENDING,
            'cloturee' => Complaint::STATUS_CLOSED,
            'en-cours-lrp' => Complaint::STATUS_ONGOING_LRP,
            self::SEARCH_APPOINTMENT_PENDING => Complaint::STATUS_APPOINTMENT_PENDING,
            self::SEARCH_ASSIGNED => Complaint::STATUS_ASSIGNED,
            self::SEARCH_ASSIGNMENT_PENDING => Complaint::STATUS_ASSIGNMENT_PENDING,
            self::SEARCH_CLOSED => Complaint::STATUS_CLOSED,
            self::SEARCH_REJECTED => Complaint::STATUS_REJECTED,
            self::SEARCH_ONGOING_LRP => Complaint::STATUS_ONGOING_LRP,
            self::SEARCH_UNIT_REDIRECTION_PENDING => Complaint::STATUS_UNIT_REDIRECTION_PENDING,
            default => null,
        };

        if (null !== $status) {
            $this->searchByStatus($qb, $status);
        } elseif (!empty($searchQuery)) {
            switch ($searchQuery) {
                case self::SEARCH_APPOINTMENT_PLANNED:
                    $this->searchWithAppointmentPlanned($qb);
                    break;
                case self::SEARCH_CLOSURE_PENDING:
                    $this->searchWithClosurePending($qb);
                    break;
                case self::SEARCH_ALERT:
                    $this->searchWithAlert($qb);
                    break;
                case self::SEARCH_REACHES_DEADLINE:
                    $this->searchWithReachesDeadline($qb);
                    break;
                case self::SEARCH_EXCEEDS_DEADLINE:
                    $this->searchWithExdeedsDealine($qb);
                    break;
                default:
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
        }

        return $qb;
    }

    private function searchByStatus(QueryBuilder $qb, ?string $status): QueryBuilder
    {
        return $qb->andWhere('c.status = :status')
            ->setParameter('status', $status);
    }

    private function searchWithAppointmentPlanned(QueryBuilder $qb): QueryBuilder
    {
        return $qb->andWhere('c.appointmentDate IS NOT NULL')
            ->andWhere('c.status <> :status')
            ->setParameter('status', Complaint::STATUS_CLOSED);
    }

    private function searchWithClosurePending(QueryBuilder $qb): QueryBuilder
    {
        return $qb->andWhere('c.appointmentDate <= :today')
            ->andWhere('c.status <> :status')
            ->setParameter('today', new \DateTimeImmutable())
            ->setParameter('status', Complaint::STATUS_CLOSED);
    }

    private function searchWithAlert(QueryBuilder $qb): QueryBuilder
    {
        return $qb->andWhere('c.alert IS NOT NULL');
    }

    private function searchWithReachesDeadline(QueryBuilder $qb): QueryBuilder
    {
        return $qb->andWhere('c.processingDeadline >= :today')
            ->andWhere('c.processingDeadline <= :threeDaysFromNow')
            ->andWhere('c.status <> :status')
            ->setParameter('today', new \DateTimeImmutable())
            ->setParameter('threeDaysFromNow', new \DateTimeImmutable('+3 days'))
            ->setParameter('status', Complaint::STATUS_CLOSED);
    }

    private function searchWithExdeedsDealine(QueryBuilder $qb): QueryBuilder
    {
        return $qb->andWhere('c.processingDeadline < :today')
            ->andWhere('c.status <> :status')
            ->setParameter('today', new \DateTimeImmutable())
            ->setParameter('status', Complaint::STATUS_CLOSED);
    }

    /**
     * @return Complaint[]
     */
    public function findComplaintsForAppointmentNow(): iterable
    {
        /** @var Complaint[] $complaints */
        $complaints = $this->createQueryBuilder('c')
            ->andWhere('c.appointmentTime <= :now')
            ->andWhere('c.appointmentSalesforceNotificationSentAt IS NULL')
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->toIterable();

        return $complaints;
    }
}
